export class InvoiceCreatedEvent {
  constructor(
    public readonly tenantId: string,
    public readonly invoiceId: string,
    public readonly actorUserId: string
  ) {}
}
