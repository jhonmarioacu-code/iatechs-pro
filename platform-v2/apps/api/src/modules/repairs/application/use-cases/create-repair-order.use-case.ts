import { Inject, Injectable } from "@nestjs/common";
import { REPAIRS_ACTIVITY_PUBLISHER, REPAIRS_ORDER_REPOSITORY } from "../../repairs.tokens";
import type { RepairsActivityPublisherPort } from "../ports/repairs-activity-publisher.port";
import type { CreateRepairOrderInput, RepairOrderRepositoryPort } from "../ports/repair-order-repository.port";
import { RepairOrderCreatedEvent } from "../../domain/events/repair-order-created.event";

@Injectable()
export class CreateRepairOrderUseCase {
  constructor(
    @Inject(REPAIRS_ORDER_REPOSITORY)
    private readonly repairOrderRepository: RepairOrderRepositoryPort,
    @Inject(REPAIRS_ACTIVITY_PUBLISHER)
    private readonly repairsActivityPublisher: RepairsActivityPublisherPort
  ) {}

  async execute(input: CreateRepairOrderInput) {
    const repairOrder = await this.repairOrderRepository.create(input);

    await this.repairsActivityPublisher.publishRepairOrderCreated(
      new RepairOrderCreatedEvent(input.tenantId, repairOrder.id, input.actorUserId)
    );

    return repairOrder;
  }
}
