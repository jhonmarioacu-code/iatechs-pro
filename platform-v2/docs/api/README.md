# API Docs V2

Swagger endpoint (local):

- `http://localhost:4000/docs`

Auth endpoints (Phase A.1):

- `POST /api/v1/auth/login`
- `POST /api/v1/auth/refresh`
- `POST /api/v1/auth/logout` (Bearer token required)
- `GET /api/v1/auth/me` (Bearer token required)

Headers:

- `x-tenant-id`: required for login, optional for refresh, enforced for protected routes.
