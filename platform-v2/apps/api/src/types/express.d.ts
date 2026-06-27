import type { RequestUser } from "../shared/auth/interfaces/request-user.interface";

declare global {
  namespace Express {
    interface Request {
      tenantId?: string;
      user?: RequestUser;
    }
  }
}

export {};

