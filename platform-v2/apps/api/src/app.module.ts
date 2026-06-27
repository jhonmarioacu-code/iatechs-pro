import { Module } from "@nestjs/common";
import { HealthModule } from "./modules/health/health.module";
import { AuthModule } from "./shared/auth/auth.module";
import { DatabaseModule } from "./shared/database/database.module";
import { TenantModule } from "./shared/tenant/tenant.module";

@Module({
  imports: [DatabaseModule, TenantModule, AuthModule, HealthModule]
})
export class AppModule {}
