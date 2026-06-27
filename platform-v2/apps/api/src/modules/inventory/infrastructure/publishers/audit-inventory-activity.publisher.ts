import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type { InventoryActivityPublisherPort } from "../../application/ports/inventory-activity-publisher.port";
import type { InventoryItemCreatedEvent } from "../../domain/events/inventory-item-created.event";
import type { InventoryStockAdjustedEvent } from "../../domain/events/inventory-stock-adjusted.event";

@Injectable()
export class AuditInventoryActivityPublisher implements InventoryActivityPublisherPort {
  private readonly logger = new Logger(AuditInventoryActivityPublisher.name);

  constructor(private readonly prisma: PrismaService) {}

  async publishInventoryItemCreated(event: InventoryItemCreatedEvent): Promise<void> {
    await this.prisma.auditLog.create({
      data: {
        tenantId: event.tenantId,
        actorUserId: event.actorUserId,
        action: "inventory.item.created",
        entityType: "inventory_item",
        entityId: event.itemId,
        metadata: {
          source: "inventory-module"
        }
      }
    });

    this.logger.log(`Inventory item created event persisted for item=${event.itemId}`);
  }

  async publishInventoryStockAdjusted(event: InventoryStockAdjustedEvent): Promise<void> {
    await this.prisma.auditLog.create({
      data: {
        tenantId: event.tenantId,
        actorUserId: event.actorUserId,
        action: "inventory.stock.adjusted",
        entityType: "inventory_item",
        entityId: event.itemId,
        metadata: {
          delta: event.delta,
          reason: event.reason,
          source: "inventory-module"
        }
      }
    });

    this.logger.log(`Inventory stock adjusted for item=${event.itemId} delta=${event.delta}`);
  }
}
