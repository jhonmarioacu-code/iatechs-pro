import { CrmLeadCreatedEvent } from "../../domain/events/crm-lead-created.event";

export interface CrmActivityPublisherPort {
  publishLeadCreated(event: CrmLeadCreatedEvent): Promise<void>;
}
