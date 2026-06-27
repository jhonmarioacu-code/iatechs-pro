export type LeadStatus = "new" | "contacted" | "qualified" | "proposal_sent" | "won" | "lost";

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

export interface PaginatedCrmLeads {
  items: CrmLead[];
  total: number;
  page: number;
  pageSize: number;
}
