# CRM module (Phase B.1 in progress)

Vertical slice implemented:

- `POST /api/v1/crm/leads` (create lead)
- `GET /api/v1/crm/leads` (list leads with filters/pagination)
- `PATCH /api/v1/crm/leads/:leadId/status` (move lead stage)

Architecture:

- `domain/`
- `application/`
- `infrastructure/`
- `presentation/`
