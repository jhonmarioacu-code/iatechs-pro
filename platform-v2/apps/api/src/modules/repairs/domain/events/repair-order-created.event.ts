export class RepairOrderCreatedEvent {
  constructor(
    public readonly tenantId: string,
    public readonly orderId: string,
    public readonly actorUserId: string
  ) {}
}
