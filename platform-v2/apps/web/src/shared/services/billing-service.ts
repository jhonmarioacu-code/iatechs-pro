import type { AuthSession } from "../types/auth";
import type { InvoiceStatus, PaginatedInvoices } from "../types/billing";
import { apiRequest } from "./api-client";

interface ListInvoicesInput {
  session: AuthSession;
  search?: string;
  status?: InvoiceStatus;
  page?: number;
  pageSize?: number;
}

interface CreateInvoiceInput {
  session: AuthSession;
  invoiceNumber?: string;
  customerId?: string;
  repairOrderId?: string;
  currency?: string;
  subtotalCents: number;
  taxCents?: number;
  discountCents?: number;
  dueAt?: string;
  notes?: string;
}

interface UpdateInvoiceStatusInput {
  session: AuthSession;
  invoiceId: string;
  status: InvoiceStatus;
  paidAt?: string;
}

export async function listInvoices(input: ListInvoicesInput): Promise<PaginatedInvoices> {
  const searchParams = new URLSearchParams();
  if (input.search) {
    searchParams.set("search", input.search);
  }
  if (input.status) {
    searchParams.set("status", input.status);
  }
  searchParams.set("page", String(input.page ?? 1));
  searchParams.set("pageSize", String(input.pageSize ?? 20));

  return apiRequest<PaginatedInvoices>(`/api/v1/billing/invoices?${searchParams.toString()}`, {
    token: input.session.accessToken,
    tenantId: input.session.tenantId
  });
}

export async function createInvoice(input: CreateInvoiceInput) {
  return apiRequest("/api/v1/billing/invoices", {
    method: "POST",
    token: input.session.accessToken,
    tenantId: input.session.tenantId,
    body: {
      invoiceNumber: input.invoiceNumber,
      customerId: input.customerId,
      repairOrderId: input.repairOrderId,
      currency: input.currency,
      subtotalCents: input.subtotalCents,
      taxCents: input.taxCents,
      discountCents: input.discountCents,
      dueAt: input.dueAt,
      notes: input.notes
    }
  });
}

export async function updateInvoiceStatus(input: UpdateInvoiceStatusInput) {
  return apiRequest(`/api/v1/billing/invoices/${input.invoiceId}/status`, {
    method: "PATCH",
    token: input.session.accessToken,
    tenantId: input.session.tenantId,
    body: {
      status: input.status,
      paidAt: input.paidAt
    }
  });
}
