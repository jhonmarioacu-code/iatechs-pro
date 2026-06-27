# Auth module (Phase A.1)

Implemented:

- Access JWT (`JWT_ACCESS_SECRET`) + refresh token rotation (`JWT_REFRESH_SECRET`).
- Refresh token persistence with hash-at-rest and JTI replay detection.
- MFA challenge flow (challenge record with expiration + single-use).
- Tenant-aware auth (`x-tenant-id`) for login and protected routes.
- RBAC base guard via `@Permissions(...)` and `PermissionsGuard`.

Endpoints:

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/refresh`
- `POST /api/v1/auth/logout`
- `GET /api/v1/auth/me`

Seed support:

- `pnpm --filter @iatechs/api prisma:seed`
