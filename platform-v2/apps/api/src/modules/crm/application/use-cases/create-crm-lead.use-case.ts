import { Inject, Injectable } from "@nestjs/common";
import { CRM_ACTIVITY_PUBLISHER, CRM_LEAD_REPOSITORY } from "../../crm.tokens";
import { CrmLeadCreatedEvent } from "../../domain/events/crm-lead-created.event";
import type { CrmActivityPublisherPort } from "../ports/crm-activity-publisher.port";
import type { CreateCrmLeadInput, CrmLeadRepositoryPort } from "../ports/crm-lead-repository.port";

@Injectable()
export class CreateCrmLeadUseCase {
  constructor(
    @Inject(CRM_LEAD_REPOSITORY)
    private readonly crmLeadRepository: CrmLeadRepositoryPort,
    @Inject(CRM_ACTIVITY_PUBLISHER)
    private readonly crmActivityPublisher: CrmActivityPublisherPort
  ) {}

  async execute(input: CreateCrmLeadInput) {
    const lead = await this.crmLeadRepository.create(input);
    await this.crmActivityPublisher.publishLeadCreated(
      new CrmLeadCreatedEvent(input.tenantId, lead.id, input.actorUserId)
    );

    return lead;
  }
}
