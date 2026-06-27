export interface RequestUser {
  sub: string;
  tenantId: string;
  email: string;
  role: string;
  permissions: string[];
  type: "access";
  jti: string;
}

