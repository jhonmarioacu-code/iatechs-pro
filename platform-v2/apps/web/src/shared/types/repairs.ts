export type RepairOrderStatus =
  | "received"
  | "diagnosing"
  | "waiting_parts"
  | "in_repair"
  | "ready_delivery"
  | "delivered"
  | "canceled";

export type RepairPriority = "low" | "normal" | "high" | "critical";

export interface RepairOrder {
  id: string;
  tenantId: string;
  customerId: string | null;
  crmLeadId: string | null;
  intakeChannel: string | null;
  deviceType: string;
  deviceBrand: string | null;
  deviceModel: string | null;
  serialNumber: string | null;
  issueSummary: string;
  diagnosisSummary: string | null;
  status: RepairOrderStatus;
  priority: RepairPriority;
  assignedTechnicianId: string | null;
  estimatedCompletionAt: string | null;
  intakeAt: string;
  completedAt: string | null;
  createdAt: string;
  updatedAt: string;
}

export interface PaginatedRepairOrders {
  items: RepairOrder[];
  total: number;
  page: number;
  pageSize: number;
}
