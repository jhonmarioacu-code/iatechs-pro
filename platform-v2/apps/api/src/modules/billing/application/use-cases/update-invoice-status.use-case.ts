import { Inject, Injectable, NotFoundException } from "@nestjs/common";
import { BILLING_ACTIVITY_PUBLISHER, BILLING_INVOICE_REPOSITORY } from "../../billing.tokens";
import { InvoiceStatusUpdatedEvent } from "../../domain/events/invoice-status-updated.event";
import { InvoiceStatus } from "../../domain/invoice-status.enum";
import type { BillingActivityPublisherPort } from "../ports/billing-activity-publisher.port";
import type { InvoiceRepositoryPort } from "../ports/invoice-repository.port";

@Injectable()
export class UpdateInvoiceStatusUseCase {
  constructor(
    @Inject(BILLING_INVOICE_REPOSITORY)
    private readonly invoiceRepository: InvoiceRepositoryPort,
    @Inject(BILLING_ACTIVITY_PUBLISHER)
    private readonly billingActivityPublisher: BillingActivityPublisherPort
  ) {}

  async execute(tenantId: string, actorUserId: string, invoiceId: string, status: InvoiceStatus, paidAt?: string) {
    const invoice = await this.invoiceRepository.updateStatus({
      tenantId,
      invoiceId,
      status,
      paidAt
    });

    if (!invoice) {
      throw new NotFoundException("Invoice not found");
    }

    await this.billingActivityPublisher.publishInvoiceStatusUpdated(
      new InvoiceStatusUpdatedEvent(tenantId, invoice.id, actorUserId, status)
    );

    return invoice;
  }
}
