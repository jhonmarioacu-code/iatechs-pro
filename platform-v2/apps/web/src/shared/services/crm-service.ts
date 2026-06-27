import type { AuthSession } from "../types/auth";
import type { LeadStatus, PaginatedCrmLeads } from "../types/crm";
import { apiRequest } from "./api-client";

interface ListCrmLeadsInput {
  session: AuthSession;
  search?: string;
  status?: LeadStatus;
  page?: number;
  pageSize?: number;
}

interface CreateLeadInput {
  session: AuthSession;
  fullName: string;
  email?: string;
  phone?: string;
  companyName?: string;
  source?: string;
  notes?: string;
  estimatedMonthlyValue?: number;
}

export async function listCrmLeads(input: ListCrmLeadsInput): Promise<PaginatedCrmLeads> {
  const searchParams = new URLSearchParams();
  if (input.search) {
    searchParams.set("search", input.search);
  }
  if (input.status) {
    searchParams.set("status", input.status);
  }
  searchParams.set("page", String(input.page ?? 1));
  searchParams.set("pageSize", String(input.pageSize ?? 20));

  return apiRequest<PaginatedCrmLeads>(`/api/v1/crm/leads?${searchParams.toString()}`, {
    token: input.session.accessToken,
    tenantId: input.session.tenantId
  });
}

export async function createCrmLead(input: CreateLeadInput) {
  return apiRequest("/api/v1/crm/leads", {
    method: "POST",
    token: input.session.accessToken,
    tenantId: input.session.tenantId,
    body: {
      fullName: input.fullName,
      email: input.email,
      phone: input.phone,
      companyName: input.companyName,
      source: input.source,
      notes: input.notes,
      estimatedMonthlyValue: input.estimatedMonthlyValue
    }
  });
}

export async function updateCrmLeadStatus(
  session: AuthSession,
  leadId: string,
  status: LeadStatus
) {
  return apiRequest(`/api/v1/crm/leads/${leadId}/status`, {
    method: "PATCH",
    token: session.accessToken,
    tenantId: session.tenantId,
    body: { status }
  });
}
