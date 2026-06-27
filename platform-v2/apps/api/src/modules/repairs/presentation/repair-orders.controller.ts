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
import { CreateRepairOrderDto } from "../application/dto/create-repair-order.dto";
import { ListRepairOrdersQueryDto } from "../application/dto/list-repair-orders-query.dto";
import { UpdateRepairOrderStatusDto } from "../application/dto/update-repair-order-status.dto";
import { CreateRepairOrderUseCase } from "../application/use-cases/create-repair-order.use-case";
import { ListRepairOrdersUseCase } from "../application/use-cases/list-repair-orders.use-case";
import { UpdateRepairOrderStatusUseCase } from "../application/use-cases/update-repair-order-status.use-case";

@ApiTags("repairs")
@ApiBearerAuth()
@UseGuards(JwtAuthGuard, PermissionsGuard)
@Controller("api/v1/repairs/orders")
export class RepairOrdersController {
  constructor(
    private readonly createRepairOrderUseCase: CreateRepairOrderUseCase,
    private readonly listRepairOrdersUseCase: ListRepairOrdersUseCase,
    private readonly updateRepairOrderStatusUseCase: UpdateRepairOrderStatusUseCase
  ) {}

  @Permissions("repairs:orders:read")
  @Get()
  list(@CurrentUser() currentUser: RequestUser | undefined, @Query() query: ListRepairOrdersQueryDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.listRepairOrdersUseCase.execute({
      tenantId: currentUser.tenantId,
      page: query.page ?? 1,
      pageSize: query.pageSize ?? 20,
      status: query.status,
      priority: query.priority,
      search: query.search?.trim()
    });
  }

  @Permissions("repairs:orders:write")
  @Post()
  create(@CurrentUser() currentUser: RequestUser | undefined, @Body() dto: CreateRepairOrderDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.createRepairOrderUseCase.execute({
      tenantId: currentUser.tenantId,
      actorUserId: currentUser.sub,
      customerId: dto.customerId,
      crmLeadId: dto.crmLeadId,
      intakeChannel: dto.intakeChannel,
      deviceType: dto.deviceType,
      deviceBrand: dto.deviceBrand,
      deviceModel: dto.deviceModel,
      serialNumber: dto.serialNumber,
      issueSummary: dto.issueSummary,
      priority: dto.priority,
      estimatedCompletionAt: dto.estimatedCompletionAt
    });
  }

  @Permissions("repairs:orders:write")
  @Patch(":orderId/status")
  updateStatus(
    @CurrentUser() currentUser: RequestUser | undefined,
    @Param("orderId", new ParseUUIDPipe()) orderId: string,
    @Body() dto: UpdateRepairOrderStatusDto
  ) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.updateRepairOrderStatusUseCase.execute(currentUser.tenantId, orderId, dto.status);
  }
}
