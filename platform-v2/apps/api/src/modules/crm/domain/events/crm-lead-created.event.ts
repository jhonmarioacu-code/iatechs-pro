export class CrmLeadCreatedEvent {
  constructor(
    public readonly tenantId: string,
    public readonly leadId: string,
    public readonly actorUserId: string
  ) {}
}
