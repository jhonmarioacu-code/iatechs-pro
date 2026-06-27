# Repairs module (Phase B.2 in progress)

Vertical slice implemented:

- `POST /api/v1/repairs/orders` (create intake order)
- `GET /api/v1/repairs/orders` (list orders with filters/pagination)
- `PATCH /api/v1/repairs/orders/:orderId/status` (update workflow stage)

Architecture:

- `domain/`
- `application/`
- `infrastructure/`
- `presentation/`
