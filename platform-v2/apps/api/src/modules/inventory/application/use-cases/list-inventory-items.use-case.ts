import { Inject, Injectable } from "@nestjs/common";
import { INVENTORY_ITEM_REPOSITORY } from "../../inventory.tokens";
import type { InventoryItemRepositoryPort, ListInventoryItemsInput } from "../ports/inventory-item-repository.port";

@Injectable()
export class ListInventoryItemsUseCase {
  constructor(
    @Inject(INVENTORY_ITEM_REPOSITORY)
    private readonly inventoryItemRepository: InventoryItemRepositoryPort
  ) {}

  execute(input: ListInventoryItemsInput) {
    return this.inventoryItemRepository.list(input);
  }
}
