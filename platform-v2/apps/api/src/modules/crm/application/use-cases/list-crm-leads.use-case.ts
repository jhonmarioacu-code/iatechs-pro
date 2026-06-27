import { Inject, Injectable } from "@nestjs/common";
import { CRM_LEAD_REPOSITORY } from "../../crm.tokens";
import type { CrmLeadRepositoryPort, ListCrmLeadsInput } from "../ports/crm-lead-repository.port";

@Injectable()
export class ListCrmLeadsUseCase {
  constructor(
    @Inject(CRM_LEAD_REPOSITORY)
    private readonly crmLeadRepository: CrmLeadRepositoryPort
  ) {}

  execute(input: ListCrmLeadsInput) {
    return this.crmLeadRepository.list(input);
  }
}
