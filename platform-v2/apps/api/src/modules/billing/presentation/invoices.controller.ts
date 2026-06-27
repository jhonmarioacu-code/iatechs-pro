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
import { CreateInvoiceDto } from "../application/dto/create-invoice.dto";
import { ListInvoicesQueryDto } from "../application/dto/list-invoices-query.dto";
import { UpdateInvoiceStatusDto } from "../application/dto/update-invoice-status.dto";
import { CreateInvoiceUseCase } from "../application/use-cases/create-invoice.use-case";
import { ListInvoicesUseCase } from "../application/use-cases/list-invoices.use-case";
import { UpdateInvoiceStatusUseCase } from "../application/use-cases/update-invoice-status.use-case";

@ApiTags("billing")
@ApiBearerAuth()
@UseGuards(JwtAuthGuard, PermissionsGuard)
@Controller("api/v1/billing/invoices")
export class InvoicesController {
  constructor(
    private readonly createInvoiceUseCase: CreateInvoiceUseCase,
    private readonly listInvoicesUseCase: ListInvoicesUseCase,
    private readonly updateInvoiceStatusUseCase: UpdateInvoiceStatusUseCase
  ) {}

  @Permissions("billing:invoices:read")
  @Get()
  list(@CurrentUser() currentUser: RequestUser | undefined, @Query() query: ListInvoicesQueryDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.listInvoicesUseCase.execute({
      tenantId: currentUser.tenantId,
      page: query.page ?? 1,
      pageSize: query.pageSize ?? 20,
      status: query.status,
      search: query.search?.trim()
    });
  }

  @Permissions("billing:invoices:write")
  @Post()
  create(@CurrentUser() currentUser: RequestUser | undefined, @Body() dto: CreateInvoiceDto) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.createInvoiceUseCase.execute({
      tenantId: currentUser.tenantId,
      actorUserId: currentUser.sub,
      invoiceNumber: dto.invoiceNumber,
      customerId: dto.customerId,
      repairOrderId: dto.repairOrderId,
      currency: dto.currency,
      subtotalCents: dto.subtotalCents,
      taxCents: dto.taxCents,
      discountCents: dto.discountCents,
      dueAt: dto.dueAt,
      notes: dto.notes
    });
  }

  @Permissions("billing:invoices:write")
  @Patch(":invoiceId/status")
  updateStatus(
    @CurrentUser() currentUser: RequestUser | undefined,
    @Param("invoiceId", new ParseUUIDPipe()) invoiceId: string,
    @Body() dto: UpdateInvoiceStatusDto
  ) {
    if (!currentUser) {
      throw new UnauthorizedException("Authenticated user context is missing");
    }

    return this.updateInvoiceStatusUseCase.execute(
      currentUser.tenantId,
      currentUser.sub,
      invoiceId,
      dto.status,
      dto.paidAt
    );
  }
}
