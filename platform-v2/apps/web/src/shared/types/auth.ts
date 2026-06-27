export interface AuthUser {
  id: string;
  tenantId: string;
  email: string;
  fullName: string;
  role: string;
  permissions: string[];
  mfaEnabled: boolean;
}

export interface AuthSession {
  tenantId: string;
  tokenType: "Bearer";
  accessToken: string;
  refreshToken: string;
  expiresIn: number;
  user: AuthUser;
}

export interface AuthTokenResponse {
  mfaRequired: false;
  tokenType: "Bearer";
  accessToken: string;
  refreshToken: string;
  expiresIn: number;
  user: AuthUser;
}

export interface MfaChallengeResponse {
  mfaRequired: true;
  mfaChallengeId: string;
  expiresAt: string;
  developmentCode?: string;
}

export type LoginResponse = AuthTokenResponse | MfaChallengeResponse;
