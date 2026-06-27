import { Injectable } from "@nestjs/common";
import { PrismaService } from "../../../../shared/database/prisma.service";
import type {
  CreateInventoryItemInput,
  InventoryItemRepositoryPort,
  InventoryStockAdjustmentInput,
  ListInventoryItemsInput,
  ListInventoryItemsResult
} from "../../application/ports/inventory-item-repository.port";
import type { InventoryItem } from "../../domain/inventory-item.entity";
import { InventoryItemStatus } from "../../domain/inventory-item-status.enum";

@Injectable()
export class PrismaInventoryItemRepository implements InventoryItemRepositoryPort {
  constructor(private readonly prisma: PrismaService) {}

  async create(input: CreateInventoryItemInput): Promise<InventoryItem> {
    const createdItem = await this.prisma.inventoryItem.create({
      data: {
        tenantId: input.tenantId,
        sku: input.sku.trim().toUpperCase(),
        name: input.name.trim(),
        description: input.description?.trim() ?? null,
        locationCode: input.locationCode?.trim() ?? null,
        quantityOnHand: input.quantityOnHand ?? 0,
        reorderPoint: input.reorderPoint ?? 0,
        unitCostCents: input.unitCostCents ?? null,
        status: input.status ?? InventoryItemStatus.ACTIVE,
        lastMovementAt: input.quantityOnHand && input.quantityOnHand > 0 ? new Date() : null
      }
    });

    return this.toEntity(createdItem);
  }

  async list(input: ListInventoryItemsInput): Promise<ListInventoryItemsResult> {
    const whereClause = {
      tenantId: input.tenantId,
      deletedAt: null,
      ...(input.status ? { status: input.status } : {}),
      ...(input.search
        ? {
            OR: [
              { sku: { contains: input.search, mode: "insensitive" as const } },
              { name: { contains: input.search, mode: "insensitive" as const } },
              { locationCode: { contains: input.search, mode: "insensitive" as const } }
            ]
          }
        : {})
    };

    const [items, total] = await this.prisma.$transaction([
      this.prisma.inventoryItem.findMany({
        where: whereClause,
        orderBy: { createdAt: "desc" },
        skip: (input.page - 1) * input.pageSize,
        take: input.pageSize
      }),
      this.prisma.inventoryItem.count({
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

  async adjustStock(input: InventoryStockAdjustmentInput): Promise<InventoryItem | null> {
    return this.prisma.$transaction(async (transactionClient) => {
      const existingItem = await transactionClient.inventoryItem.findFirst({
        where: {
          id: input.itemId,
          tenantId: input.tenantId,
          deletedAt: null
        }
      });

      if (!existingItem) {
        return null;
      }

      const updatedQuantity = existingItem.quantityOnHand + input.delta;
      if (updatedQuantity < 0) {
        return this.toEntity({
          ...existingItem,
          quantityOnHand: updatedQuantity
        });
      }

      const updatedItem = await transactionClient.inventoryItem.update({
        where: { id: input.itemId },
        data: {
          quantityOnHand: updatedQuantity,
          lastMovementAt: new Date()
        }
      });

      return this.toEntity(updatedItem);
    });
  }

  private toEntity(item: {
    id: string;
    tenantId: string;
    sku: string;
    name: string;
    description: string | null;
    locationCode: string | null;
    quantityOnHand: number;
    reorderPoint: number;
    unitCostCents: number | null;
    status: string;
    lastMovementAt: Date | null;
    createdAt: Date;
    updatedAt: Date;
  }): InventoryItem {
    return {
      id: item.id,
      tenantId: item.tenantId,
      sku: item.sku,
      name: item.name,
      description: item.description,
      locationCode: item.locationCode,
      quantityOnHand: item.quantityOnHand,
      reorderPoint: item.reorderPoint,
      unitCostCents: item.unitCostCents,
      status: item.status as InventoryItemStatus,
      lastMovementAt: item.lastMovementAt?.toISOString() ?? null,
      createdAt: item.createdAt.toISOString(),
      updatedAt: item.updatedAt.toISOString()
    };
  }
}
