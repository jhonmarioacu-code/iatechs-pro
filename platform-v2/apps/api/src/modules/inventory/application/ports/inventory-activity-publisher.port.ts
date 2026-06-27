import { InventoryItemCreatedEvent } from "../../domain/events/inventory-item-created.event";
import { InventoryStockAdjustedEvent } from "../../domain/events/inventory-stock-adjusted.event";

export interface InventoryActivityPublisherPort {
  publishInventoryItemCreated(event: InventoryItemCreatedEvent): Promise<void>;
  publishInventoryStockAdjusted(event: InventoryStockAdjustedEvent): Promise<void>;
}
