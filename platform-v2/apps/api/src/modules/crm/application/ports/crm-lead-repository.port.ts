import type { CrmLead } from "../../domain/crm-lead.entity";
import { LeadStatus } from "../../domain/lead-status.enum";

export interface CreateCrmLeadInput {
  tenantId: string;
  actorUserId: string;
  fullName: string;
  email?: string;
  phone?: string;
  companyName?: string;
  source?: string;
  notes?: string;
  estimatedMonthlyValue?: number;
}

export interface ListCrmLeadsInput {
  tenantId: string;
  page: number;
  pageSize: number;
  status?: LeadStatus;
  search?: string;
}

export interface ListCrmLeadsResult {
  items: CrmLead[];
  total: number;
  page: number;
  pageSize: number;
}

export interface CrmLeadRepositoryPort {
  create(input: CreateCrmLeadInput): Promise<CrmLead>;
  list(input: ListCrmLeadsInput): Promise<ListCrmLeadsResult>;
  updateStatus(tenantId: string, leadId: string, status: LeadStatus): Promise<CrmLead | null>;
}
