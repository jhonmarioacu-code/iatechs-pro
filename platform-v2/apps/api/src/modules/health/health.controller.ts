import { Controller, Get, Headers } from "@nestjs/common";

@Controller({ path: "api/v1/health" })
export class HealthController {
  @Get()
  getHealth(@Headers("x-tenant-id") tenantId?: string) {
    return {
      status: "ok",
      service: "iatechs-pro-v2-api",
      tenant: tenantId ?? null,
      timestamp: new Date().toISOString()
    };
  }
}
