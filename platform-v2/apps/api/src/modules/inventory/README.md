# Inventory module (Phase B.3 in progress)

Vertical slice implemented:

- `POST /api/v1/inventory/items` (create SKU and inventory item)
- `GET /api/v1/inventory/items` (list items with filters/pagination)
- `PATCH /api/v1/inventory/items/:itemId/stock` (stock adjustments)

Architecture:

- `domain/`
- `application/`
- `infrastructure/`
- `presentation/`
