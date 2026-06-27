import { InvoiceStatus } from "./invoice-status.enum";

export interface Invoice {
  id: string;
  tenantId: string;
  invoiceNumber: string;
  customerId: string | null;
  repairOrderId: string | null;
  status: InvoiceStatus;
  currency: string;
  subtotalCents: number;
  taxCents: number;
  discountCents: number;
  totalCents: number;
  issuedAt: string | null;
  dueAt: string | null;
  paidAt: string | null;
  notes: string | null;
  createdAt: string;
  updatedAt: string;
}
