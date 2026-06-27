# Quality Gates (Phase A baseline)

Required before merge:

1. `pnpm lint`
2. `pnpm typecheck`
3. `pnpm build`
4. `pnpm test`
5. Security workflow pass
6. Auth + CRM smoke test (`/auth/login`, `/portal/admin/crm/leads`, `GET /api/v1/crm/leads`)
