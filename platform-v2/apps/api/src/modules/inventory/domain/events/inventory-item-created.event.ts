export class InventoryItemCreatedEvent {
  constructor(
    public readonly tenantId: string,
    public readonly itemId: string,
    public readonly actorUserId: string
  ) {}
}
