import { LeadStatus } from "./lead-status.enum";

export interface CrmLead {
  id: string;
  tenantId: string;
  fullName: string;
  email: string | null;
  phone: string | null;
  companyName: string | null;
  source: string | null;
  notes: string | null;
  estimatedMonthlyValue: number | null;
  status: LeadStatus;
  ownerUserId: string | null;
  convertedCustomerId: string | null;
  createdAt: string;
  updatedAt: string;
}
