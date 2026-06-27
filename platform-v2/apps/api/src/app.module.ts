import { Module } from "@nestjs/common";
import { HealthModule } from "./modules/health/health.module";
import { CrmModule } from "./modules/crm/crm.module";
import { InventoryModule } from "./modules/inventory/inventory.module";
import { RepairsModule } from "./modules/repairs/repairs.module";
import { AuthModule } from "./shared/auth/auth.module";
import { DatabaseModule } from "./shared/database/database.module";
import { TenantModule } from "./shared/tenant/tenant.module";

@Module({
  imports: [DatabaseModule, TenantModule, AuthModule, CrmModule, RepairsModule, InventoryModule, HealthModule]
})
export class AppModule {}
