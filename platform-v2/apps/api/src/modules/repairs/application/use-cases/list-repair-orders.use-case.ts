import { Inject, Injectable } from "@nestjs/common";
import { REPAIRS_ORDER_REPOSITORY } from "../../repairs.tokens";
import type { ListRepairOrdersInput, RepairOrderRepositoryPort } from "../ports/repair-order-repository.port";

@Injectable()
export class ListRepairOrdersUseCase {
  constructor(
    @Inject(REPAIRS_ORDER_REPOSITORY)
    private readonly repairOrderRepository: RepairOrderRepositoryPort
  ) {}

  execute(input: ListRepairOrdersInput) {
    return this.repairOrderRepository.list(input);
  }
}
