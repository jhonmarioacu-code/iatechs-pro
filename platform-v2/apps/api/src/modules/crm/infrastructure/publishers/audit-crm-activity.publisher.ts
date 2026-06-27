import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type { CrmActivityPublisherPort } from "../../application/ports/crm-activity-publisher.port";
import type { CrmLeadCreatedEvent } from "../../domain/events/crm-lead-created.event";

@Injectable()
export class AuditCrmActivityPublisher implements CrmActivityPublisherPort {
  private readonly logger = new Logger(AuditCrmActivityPublisher.name);

  constructor(private readonly prisma: PrismaService) {}

  async publishLeadCreated(event: CrmLeadCreatedEvent): Promise<void> {
    await this.prisma.auditLog.create({
      data: {
        tenantId: event.tenantId,
        actorUserId: event.actorUserId,
        action: "crm.lead.created",
        entityType: "crm_lead",
        entityId: event.leadId,
        metadata: {
          source: "crm-module"
        }
      }
    });

    this.logger.log(`CRM lead created event persisted for lead=${event.leadId}`);
  }
}
