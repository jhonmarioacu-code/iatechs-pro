export interface AccessTokenPayload {
  sub: string;
  tenantId: string;
  email: string;
  role: string;
  permissions: string[];
  type: "access";
  jti: string;
}

export interface RefreshTokenPayload {
  sub: string;
  tenantId: string;
  type: "refresh";
  jti: string;
}

