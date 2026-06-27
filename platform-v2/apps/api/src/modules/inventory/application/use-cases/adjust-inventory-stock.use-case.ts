import { BadRequestException, Inject, Injectable, NotFoundException } from "@nestjs/common";
import { INVENTORY_ACTIVITY_PUBLISHER, INVENTORY_ITEM_REPOSITORY } from "../../inventory.tokens";
import type { InventoryActivityPublisherPort } from "../ports/inventory-activity-publisher.port";
import type { InventoryItemRepositoryPort } from "../ports/inventory-item-repository.port";
import { InventoryStockAdjustedEvent } from "../../domain/events/inventory-stock-adjusted.event";

interface AdjustInventoryStockInput {
  tenantId: string;
  actorUserId: string;
  itemId: string;
  delta: number;
  reason?: string;
}

@Injectable()
export class AdjustInventoryStockUseCase {
  constructor(
    @Inject(INVENTORY_ITEM_REPOSITORY)
    private readonly inventoryItemRepository: InventoryItemRepositoryPort,
    @Inject(INVENTORY_ACTIVITY_PUBLISHER)
    private readonly inventoryActivityPublisher: InventoryActivityPublisherPort
  ) {}

  async execute(input: AdjustInventoryStockInput) {
    if (input.delta === 0) {
      throw new BadRequestException("Stock delta cannot be zero");
    }

    const updatedItem = await this.inventoryItemRepository.adjustStock({
      tenantId: input.tenantId,
      itemId: input.itemId,
      delta: input.delta
    });

    if (!updatedItem) {
      throw new NotFoundException("Inventory item not found");
    }

    if (updatedItem.quantityOnHand < 0) {
      throw new BadRequestException("Inventory quantity cannot be negative");
    }

    await this.inventoryActivityPublisher.publishInventoryStockAdjusted(
      new InventoryStockAdjustedEvent(
        input.tenantId,
        updatedItem.id,
        input.actorUserId,
        input.delta,
        input.reason?.trim() || null
      )
    );

    return updatedItem;
  }
}
