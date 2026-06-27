import { RepairOrderCreatedEvent } from "../../domain/events/repair-order-created.event";

export interface RepairsActivityPublisherPort {
  publishRepairOrderCreated(event: RepairOrderCreatedEvent): Promise<void>;
}
