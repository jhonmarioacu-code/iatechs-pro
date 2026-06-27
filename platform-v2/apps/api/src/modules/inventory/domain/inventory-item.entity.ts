import { InventoryItemStatus } from "./inventory-item-status.enum";

export interface InventoryItem {
  id: string;
  tenantId: string;
  sku: string;
  name: string;
  description: string | null;
  locationCode: string | null;
  quantityOnHand: number;
  reorderPoint: number;
  unitCostCents: number | null;
  status: InventoryItemStatus;
  lastMovementAt: string | null;
  createdAt: string;
  updatedAt: string;
}
