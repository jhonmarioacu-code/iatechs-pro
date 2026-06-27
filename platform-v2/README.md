# IAtechs Pro V2 - Enterprise Platform V2

This workspace bootstraps the V2 target architecture:

- Frontend: Next.js + TypeScript + TailwindCSS
- Backend: NestJS + TypeScript + Swagger
- Data: PostgreSQL + Prisma
- Cache/session/rate limiting foundation: Redis
- Messaging foundation: RabbitMQ
- Infra foundation: Docker, Kubernetes, Terraform
- CRM vertical slice (backend + frontend) on top of IAM core
- Repairs vertical slice (backend + frontend) integrated with CRM and audit

## Quick start (local)

1. Start dependencies:
   - `docker compose -f infra/docker/docker-compose.local.yml up -d`
2. Install dependencies:
   - `pnpm install`
3. Prepare API data model:
   - `pnpm --filter @iatechs/api prisma:generate`
   - `pnpm --filter @iatechs/api prisma:migrate --name init_phase_a1_auth`
   - `pnpm --filter @iatechs/api prisma:migrate --name add_crm_core`
   - `pnpm --filter @iatechs/api prisma:migrate --name add_repairs_core`
   - `pnpm --filter @iatechs/api prisma:seed`
4. Start API:
   - `pnpm --filter @iatechs/api dev`
5. Start web:
   - `pnpm --filter @iatechs/web dev`

## Tech principles

- DDD + Clean Architecture + SOLID
- CQRS-ready use case boundaries
- Event-driven integration boundaries
- Multi-tenant by design (`tenant_id` everywhere)

## Current implementation status

- Phase A.1: IAM core complete (JWT, refresh rotation, MFA challenge, RBAC base).
- Phase B.1: CRM leads vertical slice in progress:
  - API: `GET/POST/PATCH /api/v1/crm/leads*`
  - Web: `/auth/login`, `/portal/admin`, `/portal/admin/crm/leads`
- Phase B.2: Repairs orders vertical slice in progress:
  - API: `GET/POST/PATCH /api/v1/repairs/orders*`
  - Web: `/portal/admin/repairs`
