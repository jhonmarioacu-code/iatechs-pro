export class InventoryStockAdjustedEvent {
  constructor(
    public readonly tenantId: string,
    public readonly itemId: string,
    public readonly actorUserId: string,
    public readonly delta: number,
    public readonly reason: string | null
  ) {}
}
