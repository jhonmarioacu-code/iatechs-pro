import {
  Body,
  Controller,
  Get,
  Param,
  ParseUUIDPipe,
  Patch,
  Post,
  Query,
  UnauthorizedException,
  UseGuards
} from "@nestjs/common";
import { ApiBearerAuth, ApiTags } from "@nestjs/swagger";
import { CurrentUser } from "../../../shared/auth/decorators/current-user.decorator";
import { Permissions } from "../../../shared/auth/decorators/permissions.decorator";
import { JwtAuthGuard } from "../../../shared/auth/guards/jwt-auth.guard";
import { PermissionsGuard } from "../../../shared/auth/guards/permissions.guard";
import type { RequestUser } from "../../../shared/auth/interfaces/request-user.interface";
import { CreateCrmLeadDto } from "../application/dto/create-crm-lead.dto";
import { ListCrmLeadsQueryDto } from "../application/dto/list-crm-leads-query.dto";
import { UpdateCrmLeadStatusDto } from "../application/dto/update-crm-lead-status.dto";
import { CreateCrmLeadUseCase } from "../application/use-cases/create-crm-lead.use-case";
import { ListCrmLeadsUseCase } from "../application/use-cases/list-crm-leads.use-case";
import { UpdateCrmLeadStatusUseCase } from "../application/use-cases/update-crm-lead-status.use-case";

@ApiTags("crm")
@ApiBearerAuth()
@UseGuards(JwtAuthGuard, PermissionsGuard)
@Controller("api/v1/crm/leads")
export class CrmLeadsController {
  constructor(
    private readonly createCrmLeadUseCase: CreateCrmLeadUseCase,
    private readonly listCrmLeadsUseCase: ListCrmLeadsUseCase,
    private readonly updateCrmLeadStatusUseCase: UpdateCrmLeadStatusUseCase
  ) {}

  @Permissions("crm:leads:read")
  @Get()
  list(@CurrentUser() currentUser: RequestUser | undefined, @Query() query: ListCrmLeadsQueryDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.listCrmLeadsUseCase.execute({
      tenantId: currentUser.tenantId,
      page: query.page ?? 1,
      pageSize: query.pageSize ?? 20,
      status: query.status,
      search: query.search?.trim()
    });
  }

  @Permissions("crm:leads:write")
  @Post()
  create(@CurrentUser() currentUser: RequestUser | undefined, @Body() dto: CreateCrmLeadDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.createCrmLeadUseCase.execute({
      tenantId: currentUser.tenantId,
      actorUserId: currentUser.sub,
      fullName: dto.fullName,
      email: dto.email,
      phone: dto.phone,
      companyName: dto.companyName,
      source: dto.source,
      notes: dto.notes,
      estimatedMonthlyValue: dto.estimatedMonthlyValue
    });
  }

  @Permissions("crm:leads:write")
  @Patch(":leadId/status")
  updateStatus(
    @CurrentUser() currentUser: RequestUser | undefined,
    @Param("leadId", new ParseUUIDPipe()) leadId: string,
    @Body() dto: UpdateCrmLeadStatusDto
  ) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.updateCrmLeadStatusUseCase.execute(currentUser.tenantId, leadId, dto.status);
  }
}
