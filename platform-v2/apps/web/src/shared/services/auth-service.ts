import type { AuthSession, LoginResponse } from "../types/auth";
import { apiRequest } from "./api-client";

interface LoginInput {
  tenantId: string;
  email: string;
  password: string;
  mfaChallengeId?: string;
  mfaCode?: string;
}

export async function login(input: LoginInput): Promise<LoginResponse> {
  return apiRequest<LoginResponse>("/api/v1/auth/login", {
    method: "POST",
    tenantId: input.tenantId,
    body: {
      email: input.email,
      password: input.password,
      mfaChallengeId: input.mfaChallengeId,
      mfaCode: input.mfaCode
    }
  });
}

export async function refreshSession(session: AuthSession): Promise<AuthSession> {
  const refreshed = await apiRequest<{
    tokenType: "Bearer";
    accessToken: string;
    refreshToken: string;
    expiresIn: number;
    user: AuthSession["user"];
  }>("/api/v1/auth/refresh", {
    method: "POST",
    tenantId: session.tenantId,
    body: {
      refreshToken: session.refreshToken
    }
  });

  return {
    tenantId: session.tenantId,
    tokenType: refreshed.tokenType,
    accessToken: refreshed.accessToken,
    refreshToken: refreshed.refreshToken,
    expiresIn: refreshed.expiresIn,
    user: refreshed.user
  };
}
