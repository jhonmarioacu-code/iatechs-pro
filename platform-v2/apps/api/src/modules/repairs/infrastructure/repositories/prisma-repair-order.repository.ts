import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type { CreateRepairOrderInput, ListRepairOrdersInput, ListRepairOrdersResult, RepairOrderRepositoryPort } from "../../application/ports/repair-order-repository.port";
import type { RepairOrder } from "../../domain/repair-order.entity";
import { RepairOrderStatus } from "../../domain/repair-order-status.enum";
import { RepairPriority } from "../../domain/repair-priority.enum";

@Injectable()
export class PrismaRepairOrderRepository implements RepairOrderRepositoryPort {
  constructor(private readonly prisma: PrismaService) {}

  async create(input: CreateRepairOrderInput): Promise<RepairOrder> {
    const createdOrder = await this.prisma.repairOrder.create({
      data: {
        tenantId: input.tenantId,
        customerId: input.customerId ?? null,
        crmLeadId: input.crmLeadId ?? null,
        intakeChannel: input.intakeChannel?.trim() ?? "portal",
        deviceType: input.deviceType.trim(),
        deviceBrand: input.deviceBrand?.trim() ?? null,
        deviceModel: input.deviceModel?.trim() ?? null,
        serialNumber: input.serialNumber?.trim() ?? null,
        issueSummary: input.issueSummary.trim(),
        status: RepairOrderStatus.RECEIVED,
        priority: input.priority ?? RepairPriority.NORMAL,
        assignedTechnicianId: null,
        estimatedCompletionAt: input.estimatedCompletionAt ? new Date(input.estimatedCompletionAt) : null
      }
    });

    return this.toEntity(createdOrder);
  }

  async list(input: ListRepairOrdersInput): Promise<ListRepairOrdersResult> {
    const whereClause = {
      tenantId: input.tenantId,
      deletedAt: null,
      ...(input.status ? { status: input.status } : {}),
      ...(input.priority ? { priority: input.priority } : {}),
      ...(input.search
        ? {
            OR: [
              { deviceType: { contains: input.search, mode: "insensitive" as const } },
              { deviceBrand: { contains: input.search, mode: "insensitive" as const } },
              { deviceModel: { contains: input.search, mode: "insensitive" as const } },
              { serialNumber: { contains: input.search, mode: "insensitive" as const } },
              { issueSummary: { contains: input.search, mode: "insensitive" as const } }
            ]
          }
        : {})
    };

    const [items, total] = await this.prisma.$transaction([
      this.prisma.repairOrder.findMany({
        where: whereClause,
        orderBy: { createdAt: "desc" },
        skip: (input.page - 1) * input.pageSize,
        take: input.pageSize
      }),
      this.prisma.repairOrder.count({
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

  async updateStatus(tenantId: string, orderId: string, status: RepairOrderStatus): Promise<RepairOrder | null> {
    const existingOrder = await this.prisma.repairOrder.findFirst({
      where: {
        id: orderId,
        tenantId,
        deletedAt: null
      }
    });

    if (!existingOrder) {
      return null;
    }

    const updatedOrder = await this.prisma.repairOrder.update({
      where: { id: orderId },
      data: {
        status,
        completedAt: status === RepairOrderStatus.DELIVERED ? new Date() : null
      }
    });

    return this.toEntity(updatedOrder);
  }

  private toEntity(order: {
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
    status: string;
    priority: string;
    assignedTechnicianId: string | null;
    estimatedCompletionAt: Date | null;
    intakeAt: Date;
    completedAt: Date | null;
    createdAt: Date;
    updatedAt: Date;
  }): RepairOrder {
    return {
      id: order.id,
      tenantId: order.tenantId,
      customerId: order.customerId,
      crmLeadId: order.crmLeadId,
      intakeChannel: order.intakeChannel,
      deviceType: order.deviceType,
      deviceBrand: order.deviceBrand,
      deviceModel: order.deviceModel,
      serialNumber: order.serialNumber,
      issueSummary: order.issueSummary,
      diagnosisSummary: order.diagnosisSummary,
      status: order.status as RepairOrderStatus,
      priority: order.priority as RepairPriority,
      assignedTechnicianId: order.assignedTechnicianId,
      estimatedCompletionAt: order.estimatedCompletionAt?.toISOString() ?? null,
      intakeAt: order.intakeAt.toISOString(),
      completedAt: order.completedAt?.toISOString() ?? null,
      createdAt: order.createdAt.toISOString(),
      updatedAt: order.updatedAt.toISOString()
    };
  }
}
