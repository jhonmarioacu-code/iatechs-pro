import { Inject, Injectable, NotFoundException } from "@nestjs/common";
import { CRM_LEAD_REPOSITORY } from "../../crm.tokens";
import { LeadStatus } from "../../domain/lead-status.enum";
import type { CrmLeadRepositoryPort } from "../ports/crm-lead-repository.port";

@Injectable()
export class UpdateCrmLeadStatusUseCase {
  constructor(
    @Inject(CRM_LEAD_REPOSITORY)
    private readonly crmLeadRepository: CrmLeadRepositoryPort
  ) {}

  async execute(tenantId: string, leadId: string, status: LeadStatus) {
    const updatedLead = await this.crmLeadRepository.updateStatus(tenantId, leadId, status);
    if (!updatedLead) {
      throw new NotFoundException("CRM lead not found");
    }

    return updatedLead;
  }
}
