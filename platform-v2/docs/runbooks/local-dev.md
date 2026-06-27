# Local Development Runbook (Phase A)

1. Copy env template:
   - `cp .env.example .env`
2. Start infra:
   - `docker compose -f infra/docker/docker-compose.local.yml up -d`
3. Install deps:
   - `pnpm install`
4. Generate Prisma client and apply DB baseline:
   - `pnpm --filter @iatechs/api prisma:generate`
   - `pnpm --filter @iatechs/api prisma:migrate --name init_phase_a1_auth`
5. Seed tenant + admin for auth tests:
   - `pnpm --filter @iatechs/api prisma:seed`
6. Start apps:
   - `pnpm --filter @iatechs/api dev`
   - `pnpm --filter @iatechs/web dev`
   - `pnpm --filter @iatechs/worker dev`

## Auth smoke test (manual)

1. Login:
   - `POST /api/v1/auth/login` with `x-tenant-id` and credentials.
2. Refresh token:
   - `POST /api/v1/auth/refresh`.
3. Profile:
   - `GET /api/v1/auth/me` with `Authorization: Bearer <access-token>`.
4. Logout:
   - `POST /api/v1/auth/logout`.
