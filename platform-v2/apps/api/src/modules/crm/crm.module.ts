import { Module } from "@nestjs/common";
import { CRM_ACTIVITY_PUBLISHER, CRM_LEAD_REPOSITORY } from "./crm.tokens";
import { CreateCrmLeadUseCase } from "./application/use-cases/create-crm-lead.use-case";
import { ListCrmLeadsUseCase } from "./application/use-cases/list-crm-leads.use-case";
import { UpdateCrmLeadStatusUseCase } from "./application/use-cases/update-crm-lead-status.use-case";
import { AuditCrmActivityPublisher } from "./infrastructure/publishers/audit-crm-activity.publisher";
import { PrismaCrmLeadRepository } from "./infrastructure/repositories/prisma-crm-lead.repository";
import { CrmLeadsController } from "./presentation/crm-leads.controller";
import { AuthModule } from "../../shared/auth/auth.module";

@Module({
  imports: [AuthModule],
  controllers: [CrmLeadsController],
  providers: [
    CreateCrmLeadUseCase,
    ListCrmLeadsUseCase,
    UpdateCrmLeadStatusUseCase,
    {
      provide: CRM_LEAD_REPOSITORY,
      useClass: PrismaCrmLeadRepository
    },
    {
      provide: CRM_ACTIVITY_PUBLISHER,
      useClass: AuditCrmActivityPublisher
    }
  ]
})
export class CrmModule {}
