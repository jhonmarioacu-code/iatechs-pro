import { RepairOrderStatus } from "./repair-order-status.enum";
import { RepairPriority } from "./repair-priority.enum";

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
