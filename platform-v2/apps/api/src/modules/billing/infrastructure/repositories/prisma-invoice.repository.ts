import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type {
  CreateInvoiceInput,
  InvoiceRepositoryPort,
  ListInvoicesInput,
  ListInvoicesResult,
  UpdateInvoiceStatusInput
} from "../../application/ports/invoice-repository.port";
import type { Invoice } from "../../domain/invoice.entity";
import { InvoiceStatus } from "../../domain/invoice-status.enum";

@Injectable()
export class PrismaInvoiceRepository implements InvoiceRepositoryPort {
  constructor(private readonly prisma: PrismaService) {}

  async create(input: CreateInvoiceInput): Promise<Invoice> {
    const subtotalCents = input.subtotalCents;
    const taxCents = input.taxCents ?? 0;
    const discountCents = input.discountCents ?? 0;
    const totalCents = subtotalCents + taxCents - discountCents;

    const createdInvoice = await this.prisma.invoice.create({
      data: {
        tenantId: input.tenantId,
        invoiceNumber: input.invoiceNumber?.trim() || this.generateInvoiceNumber(),
        customerId: input.customerId ?? null,
        repairOrderId: input.repairOrderId ?? null,
        status: InvoiceStatus.DRAFT,
        currency: (input.currency ?? "COP").trim().toUpperCase(),
        subtotalCents,
        taxCents,
        discountCents,
        totalCents,
        issuedAt: null,
        dueAt: input.dueAt ? new Date(input.dueAt) : null,
        paidAt: null,
        notes: input.notes?.trim() ?? null
      }
    });

    return this.toEntity(createdInvoice);
  }

  async list(input: ListInvoicesInput): Promise<ListInvoicesResult> {
    const whereClause = {
      tenantId: input.tenantId,
      deletedAt: null,
      ...(input.status ? { status: input.status } : {}),
      ...(input.search
        ? {
            OR: [
              { invoiceNumber: { contains: input.search, mode: "insensitive" as const } },
              { notes: { contains: input.search, mode: "insensitive" as const } }
            ]
          }
        : {})
    };

    const [items, total] = await this.prisma.$transaction([
      this.prisma.invoice.findMany({
        where: whereClause,
        orderBy: { createdAt: "desc" },
        skip: (input.page - 1) * input.pageSize,
        take: input.pageSize
      }),
      this.prisma.invoice.count({
        where: whereClause
      })
    ]);

    return {
      items: items.map((item) => this.toEntity(item)),
      total,
      page: input.page,
      pageSize: input.pageSize
    };
  }

  async updateStatus(input: UpdateInvoiceStatusInput): Promise<Invoice | null> {
    const existingInvoice = await this.prisma.invoice.findFirst({
      where: {
        id: input.invoiceId,
        tenantId: input.tenantId,
        deletedAt: null
      }
    });

    if (!existingInvoice) {
      return null;
    }

    const updatedInvoice = await this.prisma.invoice.update({
      where: { id: input.invoiceId },
      data: {
        status: input.status,
        issuedAt: input.status === InvoiceStatus.ISSUED && !existingInvoice.issuedAt ? new Date() : existingInvoice.issuedAt,
        paidAt: input.status === InvoiceStatus.PAID ? (input.paidAt ? new Date(input.paidAt) : new Date()) : null
      }
    });

    return this.toEntity(updatedInvoice);
  }

  private generateInvoiceNumber(): string {
    const now = new Date();
    const datePart = `${now.getUTCFullYear()}${String(now.getUTCMonth() + 1).padStart(2, "0")}${String(
      now.getUTCDate()
    ).padStart(2, "0")}`;
    const randomPart = Math.floor(1000 + Math.random() * 9000);
    return `INV-${datePart}-${randomPart}`;
  }

  private toEntity(invoice: {
    id: string;
    tenantId: string;
    invoiceNumber: string;
    customerId: string | null;
    repairOrderId: string | null;
    status: string;
    currency: string;
    subtotalCents: number;
    taxCents: number;
    discountCents: number;
    totalCents: number;
    issuedAt: Date | null;
    dueAt: Date | null;
    paidAt: Date | null;
    notes: string | null;
    createdAt: Date;
    updatedAt: Date;
  }): Invoice {
    return {
      id: invoice.id,
      tenantId: invoice.tenantId,
      invoiceNumber: invoice.invoiceNumber,
      customerId: invoice.customerId,
      repairOrderId: invoice.repairOrderId,
      status: invoice.status as InvoiceStatus,
      currency: invoice.currency,
      subtotalCents: invoice.subtotalCents,
      taxCents: invoice.taxCents,
      discountCents: invoice.discountCents,
      totalCents: invoice.totalCents,
      issuedAt: invoice.issuedAt?.toISOString() ?? null,
      dueAt: invoice.dueAt?.toISOString() ?? null,
      paidAt: invoice.paidAt?.toISOString() ?? null,
      notes: invoice.notes,
      createdAt: invoice.createdAt.toISOString(),
      updatedAt: invoice.updatedAt.toISOString()
    };
  }
}
