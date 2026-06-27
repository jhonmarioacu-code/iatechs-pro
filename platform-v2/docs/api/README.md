# API Docs V2

Swagger endpoint (local):

- `http://localhost:4000/docs`

Auth endpoints (Phase A.1):

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/refresh`
- `POST /api/v1/auth/logout` (Bearer token required)
- `GET /api/v1/auth/me` (Bearer token required)

CRM endpoints (Phase B.1):

- `GET /api/v1/crm/leads`
- `POST /api/v1/crm/leads`
- `PATCH /api/v1/crm/leads/:leadId/status`

Repairs endpoints (Phase B.2):

- `GET /api/v1/repairs/orders`
- `POST /api/v1/repairs/orders`
- `PATCH /api/v1/repairs/orders/:orderId/status`

Inventory endpoints (Phase B.3):

- `GET /api/v1/inventory/items`
- `POST /api/v1/inventory/items`
- `PATCH /api/v1/inventory/items/:itemId/stock`

Billing endpoints (Phase B.4):

- `GET /api/v1/billing/invoices`
- `POST /api/v1/billing/invoices`
- `PATCH /api/v1/billing/invoices/:invoiceId/status`

Headers:

- `x-tenant-id`: required for login, optional for refresh, enforced for protected routes.
