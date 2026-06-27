import type { AuthSession } from "../types/auth";
import type { InventoryItemStatus, PaginatedInventoryItems } from "../types/inventory";
import { apiRequest } from "./api-client";

interface ListInventoryItemsInput {
  session: AuthSession;
  search?: string;
  status?: InventoryItemStatus;
  page?: number;
  pageSize?: number;
}

interface CreateInventoryItemInput {
  session: AuthSession;
  sku: string;
  name: string;
  description?: string;
  locationCode?: string;
  quantityOnHand?: number;
  reorderPoint?: number;
  unitCostCents?: number;
  status?: InventoryItemStatus;
}

interface AdjustInventoryStockInput {
  session: AuthSession;
  itemId: string;
  delta: number;
  reason?: string;
}

export async function listInventoryItems(input: ListInventoryItemsInput): Promise<PaginatedInventoryItems> {
  const searchParams = new URLSearchParams();
  if (input.search) {
    searchParams.set("search", input.search);
  }
  if (input.status) {
    searchParams.set("status", input.status);
  }
  searchParams.set("page", String(input.page ?? 1));
  searchParams.set("pageSize", String(input.pageSize ?? 20));

  return apiRequest<PaginatedInventoryItems>(`/api/v1/inventory/items?${searchParams.toString()}`, {
    token: input.session.accessToken,
    tenantId: input.session.tenantId
  });
}

export async function createInventoryItem(input: CreateInventoryItemInput) {
  return apiRequest("/api/v1/inventory/items", {
    method: "POST",
    token: input.session.accessToken,
    tenantId: input.session.tenantId,
    body: {
      sku: input.sku,
      name: input.name,
      description: input.description,
      locationCode: input.locationCode,
      quantityOnHand: input.quantityOnHand,
      reorderPoint: input.reorderPoint,
      unitCostCents: input.unitCostCents,
      status: input.status
    }
  });
}

export async function adjustInventoryStock(input: AdjustInventoryStockInput) {
  return apiRequest(`/api/v1/inventory/items/${input.itemId}/stock`, {
    method: "PATCH",
    token: input.session.accessToken,
    tenantId: input.session.tenantId,
    body: {
      delta: input.delta,
      reason: input.reason
    }
  });
}
