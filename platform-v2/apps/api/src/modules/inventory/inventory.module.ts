import { Module } from "@nestjs/common";
import { INVENTORY_ACTIVITY_PUBLISHER, INVENTORY_ITEM_REPOSITORY } from "./inventory.tokens";
import { AdjustInventoryStockUseCase } from "./application/use-cases/adjust-inventory-stock.use-case";
import { CreateInventoryItemUseCase } from "./application/use-cases/create-inventory-item.use-case";
import { ListInventoryItemsUseCase } from "./application/use-cases/list-inventory-items.use-case";
import { AuditInventoryActivityPublisher } from "./infrastructure/publishers/audit-inventory-activity.publisher";
import { PrismaInventoryItemRepository } from "./infrastructure/repositories/prisma-inventory-item.repository";
import { InventoryItemsController } from "./presentation/inventory-items.controller";

@Module({
  controllers: [InventoryItemsController],
  providers: [
    CreateInventoryItemUseCase,
    ListInventoryItemsUseCase,
    AdjustInventoryStockUseCase,
    {
      provide: INVENTORY_ITEM_REPOSITORY,
      useClass: PrismaInventoryItemRepository
    },
    {
      provide: INVENTORY_ACTIVITY_PUBLISHER,
      useClass: AuditInventoryActivityPublisher
    }
  ]
})
export class InventoryModule {}
