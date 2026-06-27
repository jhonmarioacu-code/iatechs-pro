# Local Development Runbook (Phase A)

1. Copy env template:
   - `cp .env.example .env`
2. Start infra:
   - `docker compose -f infra/docker/docker-compose.local.yml up -d`
3. Install deps:
   - `pnpm install`
4. Start apps:
   - `pnpm --filter @iatechs/api dev`
   - `pnpm --filter @iatechs/web dev`
   - `pnpm --filter @iatechs/worker dev`
