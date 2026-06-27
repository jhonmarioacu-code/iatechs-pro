import { Inject, Injectable } from "@nestjs/common";
import { INVENTORY_ACTIVITY_PUBLISHER, INVENTORY_ITEM_REPOSITORY } from "../../inventory.tokens";
import type { InventoryActivityPublisherPort } from "../ports/inventory-activity-publisher.port";
import type { CreateInventoryItemInput, InventoryItemRepositoryPort } from "../ports/inventory-item-repository.port";
import { InventoryItemCreatedEvent } from "../../domain/events/inventory-item-created.event";

@Injectable()
export class CreateInventoryItemUseCase {
  constructor(
    @Inject(INVENTORY_ITEM_REPOSITORY)
    private readonly inventoryItemRepository: InventoryItemRepositoryPort,
    @Inject(INVENTORY_ACTIVITY_PUBLISHER)
    private readonly inventoryActivityPublisher: InventoryActivityPublisherPort
  ) {}

  async execute(input: CreateInventoryItemInput) {
    const inventoryItem = await this.inventoryItemRepository.create(input);

    await this.inventoryActivityPublisher.publishInventoryItemCreated(
      new InventoryItemCreatedEvent(input.tenantId, inventoryItem.id, input.actorUserId)
    );

    return inventoryItem;
  }
}
