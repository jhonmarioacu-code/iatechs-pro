import { Injectable, Logger } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type { BillingActivityPublisherPort } from "../../application/ports/billing-activity-publisher.port";
import type { InvoiceCreatedEvent } from "../../domain/events/invoice-created.event";
import type { InvoiceStatusUpdatedEvent } from "../../domain/events/invoice-status-updated.event";

@Injectable()
export class AuditBillingActivityPublisher implements BillingActivityPublisherPort {
  private readonly logger = new Logger(AuditBillingActivityPublisher.name);

  constructor(private readonly prisma: PrismaService) {}

  async publishInvoiceCreated(event: InvoiceCreatedEvent): Promise<void> {
    await this.prisma.auditLog.create({
      data: {
        tenantId: event.tenantId,
        actorUserId: event.actorUserId,
        action: "billing.invoice.created",
        entityType: "invoice",
        entityId: event.invoiceId,
        metadata: {
          source: "billing-module"
        }
      }
    });

    this.logger.log(`Billing invoice created event persisted for invoice=${event.invoiceId}`);
  }

  async publishInvoiceStatusUpdated(event: InvoiceStatusUpdatedEvent): Promise<void> {
    await this.prisma.auditLog.create({
      data: {
        tenantId: event.tenantId,
        actorUserId: event.actorUserId,
        action: "billing.invoice.status_updated",
        entityType: "invoice",
        entityId: event.invoiceId,
        metadata: {
          status: event.status,
          source: "billing-module"
        }
      }
    });

    this.logger.log(`Billing invoice status updated for invoice=${event.invoiceId} status=${event.status}`);
  }
}
