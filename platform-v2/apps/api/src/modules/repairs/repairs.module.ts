import { Module } from "@nestjs/common";
import { REPAIRS_ACTIVITY_PUBLISHER, REPAIRS_ORDER_REPOSITORY } from "./repairs.tokens";
import { CreateRepairOrderUseCase } from "./application/use-cases/create-repair-order.use-case";
import { ListRepairOrdersUseCase } from "./application/use-cases/list-repair-orders.use-case";
import { UpdateRepairOrderStatusUseCase } from "./application/use-cases/update-repair-order-status.use-case";
import { AuditRepairsActivityPublisher } from "./infrastructure/publishers/audit-repairs-activity.publisher";
import { PrismaRepairOrderRepository } from "./infrastructure/repositories/prisma-repair-order.repository";
import { RepairOrdersController } from "./presentation/repair-orders.controller";

@Module({
  controllers: [RepairOrdersController],
  providers: [
    CreateRepairOrderUseCase,
    ListRepairOrdersUseCase,
    UpdateRepairOrderStatusUseCase,
    {
      provide: REPAIRS_ORDER_REPOSITORY,
      useClass: PrismaRepairOrderRepository
    },
    {
      provide: REPAIRS_ACTIVITY_PUBLISHER,
      useClass: AuditRepairsActivityPublisher
    }
  ]
})
export class RepairsModule {}
