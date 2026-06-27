import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type { CrmLead } from "../../domain/crm-lead.entity";
import { LeadStatus } from "../../domain/lead-status.enum";
import type {
  CreateCrmLeadInput,
  CrmLeadRepositoryPort,
  ListCrmLeadsInput,
  ListCrmLeadsResult
} from "../../application/ports/crm-lead-repository.port";

@Injectable()
export class PrismaCrmLeadRepository implements CrmLeadRepositoryPort {
  constructor(private readonly prisma: PrismaService) {}

  async create(input: CreateCrmLeadInput): Promise<CrmLead> {
    const createdLead = await this.prisma.crmLead.create({
      data: {
        tenantId: input.tenantId,
        fullName: input.fullName.trim(),
        email: input.email?.trim().toLowerCase() ?? null,
        phone: input.phone?.trim() ?? null,
        companyName: input.companyName?.trim() ?? null,
        source: input.source?.trim() ?? null,
        notes: input.notes?.trim() ?? null,
        estimatedMonthlyValue: input.estimatedMonthlyValue ?? null,
        ownerUserId: input.actorUserId,
        status: LeadStatus.NEW
      }
    });

    return this.toEntity(createdLead);
  }

  async list(input: ListCrmLeadsInput): Promise<ListCrmLeadsResult> {
    const whereClause = {
      tenantId: input.tenantId,
      deletedAt: null,
      ...(input.status ? { status: input.status } : {}),
      ...(input.search
        ? {
            OR: [
              { fullName: { contains: input.search, mode: "insensitive" as const } },
              { email: { contains: input.search, mode: "insensitive" as const } },
              { companyName: { contains: input.search, mode: "insensitive" as const } }
            ]
          }
        : {})
    };

    const [items, total] = await this.prisma.$transaction([
      this.prisma.crmLead.findMany({
        where: whereClause,
        orderBy: { createdAt: "desc" },
        skip: (input.page - 1) * input.pageSize,
        take: input.pageSize
      }),
      this.prisma.crmLead.count({
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

  async updateStatus(tenantId: string, leadId: string, status: LeadStatus): Promise<CrmLead | null> {
    const lead = await this.prisma.crmLead.findFirst({
      where: {
        id: leadId,
        tenantId,
        deletedAt: null
      }
    });

    if (!lead) {
      return null;
    }

    const updatedLead = await this.prisma.crmLead.update({
      where: { id: leadId },
      data: { status }
    });

    return this.toEntity(updatedLead);
  }

  private toEntity(lead: {
    id: string;
    tenantId: string;
    fullName: string;
    email: string | null;
    phone: string | null;
    companyName: string | null;
    source: string | null;
    notes: string | null;
    estimatedMonthlyValue: number | null;
    status: string;
    ownerUserId: string | null;
    convertedCustomerId: string | null;
    createdAt: Date;
    updatedAt: Date;
  }): CrmLead {
    return {
      id: lead.id,
      tenantId: lead.tenantId,
      fullName: lead.fullName,
      email: lead.email,
      phone: lead.phone,
      companyName: lead.companyName,
      source: lead.source,
      notes: lead.notes,
      estimatedMonthlyValue: lead.estimatedMonthlyValue,
      status: lead.status as LeadStatus,
      ownerUserId: lead.ownerUserId,
      convertedCustomerId: lead.convertedCustomerId,
      createdAt: lead.createdAt.toISOString(),
      updatedAt: lead.updatedAt.toISOString()
    };
  }
}
