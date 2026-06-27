import { Inject, Injectable } from "@nestjs/common";
import { BILLING_INVOICE_REPOSITORY } from "../../billing.tokens";
import type { InvoiceRepositoryPort, ListInvoicesInput } from "../ports/invoice-repository.port";

@Injectable()
export class ListInvoicesUseCase {
  constructor(
    @Inject(BILLING_INVOICE_REPOSITORY)
    private readonly invoiceRepository: InvoiceRepositoryPort
  ) {}

  execute(input: ListInvoicesInput) {
    return this.invoiceRepository.list(input);
  }
}
