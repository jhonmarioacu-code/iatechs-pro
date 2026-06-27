import type { AuthSession } from "../types/auth";
import type { PaginatedRepairOrders, RepairOrderStatus, RepairPriority } from "../types/repairs";
import { apiRequest } from "./api-client";

interface ListRepairOrdersInput {
  session: AuthSession;
  search?: string;
  status?: RepairOrderStatus;
  priority?: RepairPriority;
  page?: number;
  pageSize?: number;
}

interface CreateRepairOrderInput {
  session: AuthSession;
  deviceType: string;
  deviceBrand?: string;
  deviceModel?: string;
  serialNumber?: string;
  issueSummary: string;
  priority?: RepairPriority;
  intakeChannel?: string;
}

export async function listRepairOrders(input: ListRepairOrdersInput): Promise<PaginatedRepairOrders> {
  const searchParams = new URLSearchParams();
  if (input.search) {
    searchParams.set("search", input.search);
  }
  if (input.status) {
    searchParams.set("status", input.status);
  }
  if (input.priority) {
    searchParams.set("priority", input.priority);
  }
  searchParams.set("page", String(input.page ?? 1));
  searchParams.set("pageSize", String(input.pageSize ?? 20));

  return apiRequest<PaginatedRepairOrders>(`/api/v1/repairs/orders?${searchParams.toString()}`, {
    token: input.session.accessToken,
    tenantId: input.session.tenantId
  });
}

export async function createRepairOrder(input: CreateRepairOrderInput) {
  return apiRequest("/api/v1/repairs/orders", {
    method: "POST",
    token: input.session.accessToken,
    tenantId: input.session.tenantId,
    body: {
      deviceType: input.deviceType,
      deviceBrand: input.deviceBrand,
      deviceModel: input.deviceModel,
      serialNumber: input.serialNumber,
      issueSummary: input.issueSummary,
      priority: input.priority,
      intakeChannel: input.intakeChannel ?? "portal-admin"
    }
  });
}

export async function updateRepairOrderStatus(
  session: AuthSession,
  orderId: string,
  status: RepairOrderStatus
) {
  return apiRequest(`/api/v1/repairs/orders/${orderId}/status`, {
    method: "PATCH",
    token: session.accessToken,
    tenantId: session.tenantId,
    body: { status }
  });
}
