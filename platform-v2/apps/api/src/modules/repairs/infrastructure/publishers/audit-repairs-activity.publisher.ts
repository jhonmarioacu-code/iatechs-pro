import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type { RepairsActivityPublisherPort } from "../../application/ports/repairs-activity-publisher.port";
import type { RepairOrderCreatedEvent } from "../../domain/events/repair-order-created.event";

@Injectable()
export class AuditRepairsActivityPublisher implements RepairsActivityPublisherPort {
  private readonly logger = new Logger(AuditRepairsActivityPublisher.name);

  constructor(private readonly prisma: PrismaService) {}

  async publishRepairOrderCreated(event: RepairOrderCreatedEvent): Promise<void> {
    await this.prisma.auditLog.create({
      data: {
        tenantId: event.tenantId,
        actorUserId: event.actorUserId,
        action: "repairs.order.created",
        entityType: "repair_order",
        entityId: event.orderId,
        metadata: {
          source: "repairs-module"
        }
      }
    });

    this.logger.log(`Repair order created event persisted for order=${event.orderId}`);
  }
}
