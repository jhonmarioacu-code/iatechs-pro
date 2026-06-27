import { BadRequestException, Injectable, UnauthorizedException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import { Prisma, type User } from "@prisma/client";
import { compare } from "bcryptjs";
import { createHash, randomInt, randomUUID } from "node:crypto";
import { PrismaService } from "../database/prisma.service";
import { LoginDto } from "./dto/login.dto";
import { LogoutDto } from "./dto/logout.dto";
import { RefreshTokenDto } from "./dto/refresh-token.dto";
import type { RequestUser } from "./interfaces/request-user.interface";
import type { AccessTokenPayload, RefreshTokenPayload } from "./types/jwt-payload.type";
import { durationToMilliseconds, durationToSeconds } from "./utils/duration.util";

type PrismaLikeClient = PrismaService | Prisma.TransactionClient;

export interface AuthUserView {
  id: string;
  tenantId: string;
  email: string;
  fullName: string;
  role: string;
  permissions: string[];
  mfaEnabled: boolean;
}

export interface AuthTokenResponse {
  mfaRequired: false;
  tokenType: "Bearer";
  accessToken: string;
  refreshToken: string;
  expiresIn: number;
  user: AuthUserView;
}

export interface MfaChallengeResponse {
  mfaRequired: true;
  mfaChallengeId: string;
  expiresAt: string;
  developmentCode?: string;
}

export type LoginResponse = AuthTokenResponse | MfaChallengeResponse;

export interface LogoutResponse {
  success: true;
  revoked: number;
}

@Injectable()
export class AuthService {
  constructor(
    private readonly prisma: PrismaService,
    private readonly jwtService: JwtService
  ) {}

  async login(dto: LoginDto, requestTenantId: string | undefined): Promise<LoginResponse> {
    const tenant = await this.resolveTenant(requestTenantId);
    const email = dto.email.trim().toLowerCase();

    const user = await this.prisma.user.findFirst({
      where: {
        tenantId: tenant.id,
        email,
        isActive: true,
        deletedAt: null
      }
    });

    if (!user) {
      throw new UnauthorizedException("Invalid credentials");
    }

    const passwordIsValid = await this.validatePassword(dto.password, user.passwordHash);
    if (!passwordIsValid) {
      throw new UnauthorizedException("Invalid credentials");
    }

    if (user.mfaEnabled) {
      if (!dto.mfaChallengeId || !dto.mfaCode) {
        return this.createMfaChallenge(user);
      }

      await this.consumeMfaChallenge(user, dto.mfaChallengeId, dto.mfaCode);
    }

    const issuedTokens = await this.issueTokenPair(this.prisma, user);

    await this.prisma.user.update({
      where: { id: user.id },
      data: { lastLoginAt: new Date() }
    });

    return issuedTokens.tokenResponse;
  }

  async refresh(dto: RefreshTokenDto, requestTenantId: string | undefined): Promise<AuthTokenResponse> {
    const payload = await this.verifyRefreshToken(dto.refreshToken);

    if (requestTenantId && requestTenantId !== "public" && requestTenantId !== payload.tenantId) {
      throw new UnauthorizedException("Tenant mismatch for refresh token");
    }

    const incomingTokenHash = this.hashValue(dto.refreshToken);

    return this.prisma.$transaction(async (transactionClient) => {
      const existingToken = await transactionClient.refreshToken.findUnique({
        where: { jti: payload.jti }
      });

      if (!existingToken || existingToken.userId !== payload.sub || existingToken.tenantId !== payload.tenantId) {
        throw new UnauthorizedException("Refresh token is invalid");
      }

      if (existingToken.revokedAt !== null) {
        await this.revokeAllUserSessions(
          transactionClient,
          existingToken.tenantId,
          existingToken.userId,
          "refresh-token-reuse"
        );
        throw new UnauthorizedException("Refresh token reuse detected");
      }

      if (existingToken.tokenHash !== incomingTokenHash) {
        await this.revokeAllUserSessions(
          transactionClient,
          existingToken.tenantId,
          existingToken.userId,
          "refresh-token-hash-mismatch"
        );
        throw new UnauthorizedException("Refresh token is invalid");
      }

      if (existingToken.expiresAt.getTime() <= Date.now()) {
        await transactionClient.refreshToken.update({
          where: { id: existingToken.id },
          data: {
            revokedAt: new Date(),
            revokedReason: "refresh-token-expired"
          }
        });
        throw new UnauthorizedException("Refresh token has expired");
      }

      const user = await transactionClient.user.findFirst({
        where: {
          id: existingToken.userId,
          tenantId: existingToken.tenantId,
          isActive: true,
          deletedAt: null
        }
      });

      if (!user) {
        throw new UnauthorizedException("User is inactive or missing");
      }

      const rotatedTokens = await this.issueTokenPair(transactionClient, user);

      await transactionClient.refreshToken.update({
        where: { id: existingToken.id },
        data: {
          revokedAt: new Date(),
          revokedReason: "rotated",
          replacedByTokenId: rotatedTokens.refreshTokenId
        }
      });

      return rotatedTokens.tokenResponse;
    });
  }

  async logout(currentUser: RequestUser, dto: LogoutDto): Promise<LogoutResponse> {
    if (dto.allSessions === true) {
      const result = await this.prisma.refreshToken.updateMany({
        where: {
          tenantId: currentUser.tenantId,
          userId: currentUser.sub,
          revokedAt: null
        },
        data: {
          revokedAt: new Date(),
          revokedReason: "logout-all-sessions"
        }
      });

      return {
        success: true,
        revoked: result.count
      };
    }

    if (!dto.refreshToken) {
      return {
        success: true,
        revoked: 0
      };
    }

    const payload = await this.verifyRefreshToken(dto.refreshToken);
    if (payload.sub !== currentUser.sub || payload.tenantId !== currentUser.tenantId) {
      throw new UnauthorizedException("Refresh token does not belong to current session user");
    }

    const result = await this.prisma.refreshToken.updateMany({
      where: {
        tenantId: payload.tenantId,
        userId: payload.sub,
        jti: payload.jti,
        revokedAt: null
      },
      data: {
        revokedAt: new Date(),
        revokedReason: "logout"
      }
    });

    return {
      success: true,
      revoked: result.count
    };
  }

  async me(currentUser: RequestUser): Promise<AuthUserView> {
    const user = await this.prisma.user.findFirst({
      where: {
        id: currentUser.sub,
        tenantId: currentUser.tenantId,
        isActive: true,
        deletedAt: null
      }
    });

    if (!user) {
      throw new UnauthorizedException("User not found");
    }

    return this.mapUser(user);
  }

  private async issueTokenPair(
    client: PrismaLikeClient,
    user: User
  ): Promise<{ tokenResponse: AuthTokenResponse; refreshTokenId: string }> {
    const accessPayload: AccessTokenPayload = {
      sub: user.id,
      tenantId: user.tenantId,
      email: user.email,
      role: user.role,
      permissions: this.normalizePermissions(user.permissions),
      type: "access",
      jti: randomUUID()
    };

    const refreshPayload: RefreshTokenPayload = {
      sub: user.id,
      tenantId: user.tenantId,
      type: "refresh",
      jti: randomUUID()
    };

    const [accessToken, refreshToken] = await Promise.all([
      this.jwtService.signAsync(accessPayload, {
        secret: this.getAccessSecret(),
        expiresIn: this.getAccessTtl()
      }),
      this.jwtService.signAsync(refreshPayload, {
        secret: this.getRefreshSecret(),
        expiresIn: this.getRefreshTtl()
      })
    ]);

    const refreshTokenRecord = await client.refreshToken.create({
      data: {
        tenantId: user.tenantId,
        userId: user.id,
        jti: refreshPayload.jti,
        tokenHash: this.hashValue(refreshToken),
        expiresAt: new Date(Date.now() + this.getRefreshTtlMilliseconds())
      }
    });

    return {
      refreshTokenId: refreshTokenRecord.id,
      tokenResponse: {
        mfaRequired: false,
        tokenType: "Bearer",
        accessToken,
        refreshToken,
        expiresIn: this.getAccessTtlSeconds(),
        user: this.mapUser(user)
      }
    };
  }

  private async verifyRefreshToken(token: string): Promise<RefreshTokenPayload> {
    try {
      const payload = await this.jwtService.verifyAsync<RefreshTokenPayload>(token, {
        secret: this.getRefreshSecret()
      });

      if (payload.type !== "refresh") {
        throw new UnauthorizedException("Invalid refresh token type");
      }

      return payload;
    } catch {
      throw new UnauthorizedException("Invalid refresh token");
    }
  }

  private async createMfaChallenge(user: User): Promise<MfaChallengeResponse> {
    const mfaCode = this.generateMfaCode();
    const expiresAt = new Date(Date.now() + this.getMfaChallengeTtlMilliseconds());

    const challenge = await this.prisma.mfaChallenge.create({
      data: {
        tenantId: user.tenantId,
        userId: user.id,
        challengeHash: this.hashValue(mfaCode),
        expiresAt
      }
    });

    return {
      mfaRequired: true,
      mfaChallengeId: challenge.id,
      expiresAt: challenge.expiresAt.toISOString(),
      ...(process.env.NODE_ENV === "production" ? {} : { developmentCode: mfaCode })
    };
  }

  private async consumeMfaChallenge(user: User, challengeId: string, mfaCode: string): Promise<void> {
    const challenge = await this.prisma.mfaChallenge.findFirst({
      where: {
        id: challengeId,
        tenantId: user.tenantId,
        userId: user.id,
        consumedAt: null,
        expiresAt: { gt: new Date() }
      }
    });

    if (!challenge) {
      throw new UnauthorizedException("MFA challenge is invalid or expired");
    }

    if (challenge.challengeHash !== this.hashValue(mfaCode.trim())) {
      throw new UnauthorizedException("Invalid MFA code");
    }

    await this.prisma.mfaChallenge.update({
      where: { id: challenge.id },
      data: { consumedAt: new Date() }
    });
  }

  private async resolveTenant(requestTenantId: string | undefined): Promise<{ id: string; slug: string }> {
    const tenantContext = requestTenantId?.trim() ?? "";
    if (tenantContext === "" || tenantContext === "public") {
      throw new BadRequestException("x-tenant-id header is required");
    }

    const tenant = await this.prisma.tenant.findFirst({
      where: {
        deletedAt: null,
        status: "active",
        OR: [{ id: tenantContext }, { slug: tenantContext }]
      },
      select: {
        id: true,
        slug: true
      }
    });

    if (!tenant) {
      throw new UnauthorizedException("Tenant context is invalid");
    }

    return tenant;
  }

  private async revokeAllUserSessions(
    client: PrismaLikeClient,
    tenantId: string,
    userId: string,
    reason: string
  ): Promise<void> {
    await client.refreshToken.updateMany({
      where: {
        tenantId,
        userId,
        revokedAt: null
      },
      data: {
        revokedAt: new Date(),
        revokedReason: reason
      }
    });
  }

  private async validatePassword(plainPassword: string, hashedPassword: string): Promise<boolean> {
    try {
      return await compare(plainPassword, hashedPassword);
    } catch {
      return false;
    }
  }

  private mapUser(user: User): AuthUserView {
    return {
      id: user.id,
      tenantId: user.tenantId,
      email: user.email,
      fullName: user.fullName,
      role: user.role,
      permissions: this.normalizePermissions(user.permissions),
      mfaEnabled: user.mfaEnabled
    };
  }

  private normalizePermissions(rawPermissions: Prisma.JsonValue | null): string[] {
    if (!Array.isArray(rawPermissions)) {
      return [];
    }

    return rawPermissions.filter((item): item is string => typeof item === "string");
  }

  private hashValue(value: string): string {
    return createHash("sha256").update(value).digest("hex");
  }

  private generateMfaCode(): string {
    return randomInt(100_000, 1_000_000).toString();
  }

  private getAccessSecret(): string {
    const secret = process.env.JWT_ACCESS_SECRET;
    if (!secret) {
      throw new Error("JWT_ACCESS_SECRET is not configured");
    }
    return secret;
  }

  private getRefreshSecret(): string {
    const secret = process.env.JWT_REFRESH_SECRET;
    if (!secret) {
      throw new Error("JWT_REFRESH_SECRET is not configured");
    }
    return secret;
  }

  private getAccessTtl(): string {
    return process.env.JWT_ACCESS_TTL ?? "900s";
  }

  private getRefreshTtl(): string {
    return process.env.JWT_REFRESH_TTL ?? "30d";
  }

  private getMfaChallengeTtl(): string {
    return process.env.MFA_CHALLENGE_TTL ?? "300s";
  }

  private getAccessTtlSeconds(): number {
    return durationToSeconds(this.getAccessTtl(), 900);
  }

  private getRefreshTtlMilliseconds(): number {
    return durationToMilliseconds(this.getRefreshTtl(), 2_592_000_000);
  }

  private getMfaChallengeTtlMilliseconds(): number {
    return durationToMilliseconds(this.getMfaChallengeTtl(), 300_000);
  }
}

