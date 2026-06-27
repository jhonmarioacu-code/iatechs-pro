import { Inject, Injectable, NotFoundException } from "@nestjs/common";
import { REPAIRS_ORDER_REPOSITORY } from "../../repairs.tokens";
import { RepairOrderStatus } from "../../domain/repair-order-status.enum";
import type { RepairOrderRepositoryPort } from "../ports/repair-order-repository.port";

@Injectable()
export class UpdateRepairOrderStatusUseCase {
  constructor(
    @Inject(REPAIRS_ORDER_REPOSITORY)
    private readonly repairOrderRepository: RepairOrderRepositoryPort
  ) {}

  async execute(tenantId: string, orderId: string, status: RepairOrderStatus) {
    const updatedOrder = await this.repairOrderRepository.updateStatus(tenantId, orderId, status);
    if (!updatedOrder) {
      throw new NotFoundException("Repair order not found");
    }

    return updatedOrder;
  }
}
