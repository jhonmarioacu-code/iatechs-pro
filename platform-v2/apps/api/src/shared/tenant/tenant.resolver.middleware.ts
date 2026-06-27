import type { NextFunction, Request, Response } from "express";

export class TenantResolverMiddleware {
  use(request: Request & { tenantId?: string }, response: Response, next: NextFunction) {
    const tenantId = request.header("x-tenant-id") ?? "public";
    request.tenantId = tenantId;
    response.setHeader("x-tenant-context", tenantId);
    next();
  }
}
