import type { Invoice } from "../../domain/invoice.entity";
import { InvoiceStatus } from "../../domain/invoice-status.enum";

export interface CreateInvoiceInput {
  tenantId: string;
  actorUserId: string;
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

export interface ListInvoicesInput {
  tenantId: string;
  page: number;
  pageSize: number;
  status?: InvoiceStatus;
  search?: string;
}

export interface ListInvoicesResult {
  items: Invoice[];
  total: number;
  page: number;
  pageSize: number;
}

export interface UpdateInvoiceStatusInput {
  tenantId: string;
  invoiceId: string;
  status: InvoiceStatus;
  paidAt?: string;
}

export interface InvoiceRepositoryPort {
  create(input: CreateInvoiceInput): Promise<Invoice>;
  list(input: ListInvoicesInput): Promise<ListInvoicesResult>;
  updateStatus(input: UpdateInvoiceStatusInput): Promise<Invoice | null>;
}
