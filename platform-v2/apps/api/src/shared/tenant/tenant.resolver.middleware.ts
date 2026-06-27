import { Injectable, type NestMiddleware } from "@nestjs/common";
import type { NextFunction, Request, Response } from "express";

@Injectable()
export class TenantResolverMiddleware implements NestMiddleware {
  use(request: Request, response: Response, next: NextFunction) {
    const tenantId = request.header("x-tenant-id") ?? "public";
    request.tenantId = tenantId;
    response.setHeader("x-tenant-context", tenantId);
    next();
  }
}
