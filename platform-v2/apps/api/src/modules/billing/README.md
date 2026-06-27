# Billing module (Phase B.4 in progress)

Vertical slice implemented:

- `POST /api/v1/billing/invoices` (create invoice draft)
- `GET /api/v1/billing/invoices` (list invoices with filters/pagination)
- `PATCH /api/v1/billing/invoices/:invoiceId/status` (transition invoice lifecycle)

Architecture:

- `domain/`
- `application/`
- `infrastructure/`
- `presentation/`
