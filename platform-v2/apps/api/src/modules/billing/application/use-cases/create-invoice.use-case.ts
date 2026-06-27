import { Inject, Injectable } from "@nestjs/common";
import { BILLING_ACTIVITY_PUBLISHER, BILLING_INVOICE_REPOSITORY } from "../../billing.tokens";
import type { BillingActivityPublisherPort } from "../ports/billing-activity-publisher.port";
import type { CreateInvoiceInput, InvoiceRepositoryPort } from "../ports/invoice-repository.port";
import { InvoiceCreatedEvent } from "../../domain/events/invoice-created.event";

@Injectable()
export class CreateInvoiceUseCase {
  constructor(
    @Inject(BILLING_INVOICE_REPOSITORY)
    private readonly invoiceRepository: InvoiceRepositoryPort,
    @Inject(BILLING_ACTIVITY_PUBLISHER)
    private readonly billingActivityPublisher: BillingActivityPublisherPort
  ) {}

  async execute(input: CreateInvoiceInput) {
    const invoice = await this.invoiceRepository.create(input);

    await this.billingActivityPublisher.publishInvoiceCreated(
      new InvoiceCreatedEvent(input.tenantId, invoice.id, input.actorUserId)
    );

    return invoice;
  }
}
