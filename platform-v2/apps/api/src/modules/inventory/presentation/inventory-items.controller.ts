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
import { AdjustInventoryStockDto } from "../application/dto/adjust-inventory-stock.dto";
import { CreateInventoryItemDto } from "../application/dto/create-inventory-item.dto";
import { ListInventoryItemsQueryDto } from "../application/dto/list-inventory-items-query.dto";
import { AdjustInventoryStockUseCase } from "../application/use-cases/adjust-inventory-stock.use-case";
import { CreateInventoryItemUseCase } from "../application/use-cases/create-inventory-item.use-case";
import { ListInventoryItemsUseCase } from "../application/use-cases/list-inventory-items.use-case";

@ApiTags("inventory")
@ApiBearerAuth()
@UseGuards(JwtAuthGuard, PermissionsGuard)
@Controller("api/v1/inventory/items")
export class InventoryItemsController {
  constructor(
    private readonly createInventoryItemUseCase: CreateInventoryItemUseCase,
    private readonly listInventoryItemsUseCase: ListInventoryItemsUseCase,
    private readonly adjustInventoryStockUseCase: AdjustInventoryStockUseCase
  ) {}

  @Permissions("inventory:items:read")
  @Get()
  list(@CurrentUser() currentUser: RequestUser | undefined, @Query() query: ListInventoryItemsQueryDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.listInventoryItemsUseCase.execute({
      tenantId: currentUser.tenantId,
      page: query.page ?? 1,
      pageSize: query.pageSize ?? 20,
      status: query.status,
      search: query.search?.trim()
    });
  }

  @Permissions("inventory:items:write")
  @Post()
  create(@CurrentUser() currentUser: RequestUser | undefined, @Body() dto: CreateInventoryItemDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.createInventoryItemUseCase.execute({
      tenantId: currentUser.tenantId,
      actorUserId: currentUser.sub,
      sku: dto.sku,
      name: dto.name,
      description: dto.description,
      locationCode: dto.locationCode,
      quantityOnHand: dto.quantityOnHand,
      reorderPoint: dto.reorderPoint,
      unitCostCents: dto.unitCostCents,
      status: dto.status
    });
  }

  @Permissions("inventory:items:write")
  @Patch(":itemId/stock")
  adjustStock(
    @CurrentUser() currentUser: RequestUser | undefined,
    @Param("itemId", new ParseUUIDPipe()) itemId: string,
    @Body() dto: AdjustInventoryStockDto
  ) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.adjustInventoryStockUseCase.execute({
      tenantId: currentUser.tenantId,
      actorUserId: currentUser.sub,
      itemId,
      delta: dto.delta,
      reason: dto.reason
    });
  }
}
