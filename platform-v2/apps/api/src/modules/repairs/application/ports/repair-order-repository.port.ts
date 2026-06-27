import type { RepairOrder } from "../../domain/repair-order.entity";
import { RepairOrderStatus } from "../../domain/repair-order-status.enum";
import { RepairPriority } from "../../domain/repair-priority.enum";

export interface CreateRepairOrderInput {
  tenantId: string;
  actorUserId: string;
  customerId?: string;
  crmLeadId?: string;
  intakeChannel?: string;
  deviceType: string;
  deviceBrand?: string;
  deviceModel?: string;
  serialNumber?: string;
  issueSummary: string;
  priority?: RepairPriority;
  estimatedCompletionAt?: string;
}

export interface ListRepairOrdersInput {
  tenantId: string;
  page: number;
  pageSize: number;
  status?: RepairOrderStatus;
  priority?: RepairPriority;
  search?: string;
}

export interface ListRepairOrdersResult {
  items: RepairOrder[];
  total: number;
  page: number;
  pageSize: number;
}

export interface RepairOrderRepositoryPort {
  create(input: CreateRepairOrderInput): Promise<RepairOrder>;
  list(input: ListRepairOrdersInput): Promise<ListRepairOrdersResult>;
  updateStatus(tenantId: string, orderId: string, status: RepairOrderStatus): Promise<RepairOrder | null>;
}
