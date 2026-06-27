import { InvoiceStatus } from "../invoice-status.enum";

export class InvoiceStatusUpdatedEvent {
  constructor(
    public readonly tenantId: string,
    public readonly invoiceId: string,
    public readonly actorUserId: string,
    public readonly status: InvoiceStatus
  ) {}
}
