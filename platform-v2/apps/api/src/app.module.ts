import { Module } from "@nestjs/common";
import { HealthModule } from "./modules/health/health.module";
import { TenantModule } from "./shared/tenant/tenant.module";

@Module({
  imports: [TenantModule, HealthModule]
})
export class AppModule {}
