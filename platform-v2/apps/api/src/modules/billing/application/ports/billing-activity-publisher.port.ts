import { InvoiceCreatedEvent } from "../../domain/events/invoice-created.event";
import { InvoiceStatusUpdatedEvent } from "../../domain/events/invoice-status-updated.event";

export interface BillingActivityPublisherPort {
  publishInvoiceCreated(event: InvoiceCreatedEvent): Promise<void>;
  publishInvoiceStatusUpdated(event: InvoiceStatusUpdatedEvent): Promise<void>;
}
