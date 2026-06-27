import { CanActivate, ExecutionContext, ForbiddenException, Injectable, UnauthorizedException } from "@nestjs/common";
import { JwtService } from "@nestjs/jwt";
import type { Request } from "express";
import type { AccessTokenPayload } from "../types/jwt-payload.type";

@Injectable()
export class JwtAuthGuard implements CanActivate {
  constructor(private readonly jwtService: JwtService) {}

  async canActivate(context: ExecutionContext): Promise<boolean> {
    const request = context.switchToHttp().getRequest<Request>();
    const authorizationHeader = request.headers.authorization;

    if (!authorizationHeader || !authorizationHeader.startsWith("Bearer ")) {
      throw new UnauthorizedException("Missing bearer token");
    }

    const token = authorizationHeader.replace("Bearer ", "").trim();
    if (token === "") {
      throw new UnauthorizedException("Bearer token is empty");
    }

    let payload: AccessTokenPayload;
    try {
      payload = await this.jwtService.verifyAsync<AccessTokenPayload>(token, {
        secret: this.getAccessSecret()
      });
    } catch {
      throw new UnauthorizedException("Invalid access token");
    }

    if (payload.type !== "access") {
      throw new UnauthorizedException("Invalid access token type");
    }

    const tenantHeader = request.tenantId;
    if (tenantHeader && tenantHeader !== "public" && tenantHeader !== payload.tenantId) {
      throw new ForbiddenException("Tenant mismatch");
    }

    request.tenantId = payload.tenantId;
    request.user = payload;

    return true;
  }

  private getAccessSecret(): string {
    const secret = process.env.JWT_ACCESS_SECRET;
    if (!secret) {
      throw new UnauthorizedException("JWT access secret is not configured");
    }
    return secret;
  }
}

