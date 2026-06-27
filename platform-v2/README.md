# IAtechs Pro V2 - Phase A Foundation

This workspace bootstraps the V2 target architecture:

- Frontend: Next.js + TypeScript + TailwindCSS
- Backend: NestJS + TypeScript + Swagger
- Data: PostgreSQL + Prisma
- Cache/session/rate limiting foundation: Redis
- Messaging foundation: RabbitMQ
- Infra foundation: Docker, Kubernetes, Terraform

## Quick start (Phase A local)

1. Start dependencies:
   - `docker compose -f infra/docker/docker-compose.local.yml up -d`
2. Install dependencies:
   - `pnpm install`
3. Start API:
   - `pnpm --filter @iatechs/api dev`
4. Start web:
   - `pnpm --filter @iatechs/web dev`

## Tech principles

- DDD + Clean Architecture + SOLID
- CQRS-ready use case boundaries
- Event-driven integration boundaries
- Multi-tenant by design (`tenant_id` everywhere)
