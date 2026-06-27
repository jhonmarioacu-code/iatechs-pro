import { Module } from "@nestjs/common";
import { BILLING_ACTIVITY_PUBLISHER, BILLING_INVOICE_REPOSITORY } from "./billing.tokens";
import { CreateInvoiceUseCase } from "./application/use-cases/create-invoice.use-case";
import { ListInvoicesUseCase } from "./application/use-cases/list-invoices.use-case";
import { UpdateInvoiceStatusUseCase } from "./application/use-cases/update-invoice-status.use-case";
import { AuditBillingActivityPublisher } from "./infrastructure/publishers/audit-billing-activity.publisher";
import { PrismaInvoiceRepository } from "./infrastructure/repositories/prisma-invoice.repository";
import { InvoicesController } from "./presentation/invoices.controller";
import { AuthModule } from "../../shared/auth/auth.module";

@Module({
  imports: [AuthModule],
  controllers: [InvoicesController],
  providers: [
    CreateInvoiceUseCase,
    ListInvoicesUseCase,
    UpdateInvoiceStatusUseCase,
    {
      provide: BILLING_INVOICE_REPOSITORY,
      useClass: PrismaInvoiceRepository
    },
    {
      provide: BILLING_ACTIVITY_PUBLISHER,
      useClass: AuditBillingActivityPublisher
    }
  ]
})
export class BillingModule {}
