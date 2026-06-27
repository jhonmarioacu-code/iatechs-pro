import type { InventoryItem } from "../../domain/inventory-item.entity";
import { InventoryItemStatus } from "../../domain/inventory-item-status.enum";

export interface CreateInventoryItemInput {
  tenantId: string;
  actorUserId: string;
  sku: string;
  name: string;
  description?: string;
  locationCode?: string;
  quantityOnHand?: number;
  reorderPoint?: number;
  unitCostCents?: number;
  status?: InventoryItemStatus;
}

export interface ListInventoryItemsInput {
  tenantId: string;
  page: number;
  pageSize: number;
  status?: InventoryItemStatus;
  search?: string;
}

export interface ListInventoryItemsResult {
  items: InventoryItem[];
  total: number;
  page: number;
  pageSize: number;
}

export interface InventoryStockAdjustmentInput {
  tenantId: string;
  itemId: string;
  delta: number;
}

export interface InventoryItemRepositoryPort {
  create(input: CreateInventoryItemInput): Promise<InventoryItem>;
  list(input: ListInventoryItemsInput): Promise<ListInventoryItemsResult>;
  adjustStock(input: InventoryStockAdjustmentInput): Promise<InventoryItem | null>;
}
