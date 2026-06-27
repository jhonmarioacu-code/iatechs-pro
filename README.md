# IAtechs Pro

Enterprise multi-tenant SaaS platform for technical service companies.

## Tech Stack

- Backend: PHP 8.4, Laravel 12
- Data: PostgreSQL
- Cache/Queue/Session/Realtime: Redis + Horizon + Reverb
- Frontend: Blade + Vite + Tailwind + Alpine.js
- Auth/API: Laravel session auth + Sanctum protected APIs
- RBAC: Spatie Permissions

## Core Capabilities

- Multi-tenant isolation by `company_id`
- Role-based portals: admin, company, technician, customer
- Modular domain architecture under `app/Domains/*`
- Enterprise modules: CRM, tickets, diagnostics, repairs, invoices, payments, inventory, purchasing, BI, compliance, document management, AI assistant, and more

## Local Setup (without Docker)

1. Install dependencies:
   - `composer install`
   - `npm ci`
2. Prepare environment:
   - `cp .env.example .env`
   - `php artisan key:generate`
3. Configure PostgreSQL/Redis credentials in `.env`.
4. Run migrations and seeders:
   - `php artisan migrate --seed`
5. Build frontend:
   - `npm run build`
6. Start services:
   - `php artisan serve`
   - `php artisan queue:work`
   - `php artisan schedule:work`

## Docker Setup

1. Create `.env` from `.env.example` and set production-safe values.
2. Run:
   - `docker compose up -d --build`
3. Open:
   - `http://localhost:8080/health`
   - `http://localhost:8080/api/health`

Services included in `docker-compose.yml`:

- `app` (Laravel + Nginx + PHP-FPM + queue worker + scheduler)
- `postgres` (PostgreSQL 17)
- `redis` (Redis 7)
- Optional profile `observability`: `prometheus`, `alertmanager`, `grafana`

## Quality Gates

- Run tests: `composer test`
- Static analysis: `composer analyse`
- Security scanning workflow: `.github/workflows/security.yml`
- Architecture audit: `php artisan iatechs:audit-architecture`
- Release gate: `php artisan iatechs:gate-release`

## Security Baseline

- CSRF protection on web forms
- Auth and per-route RBAC
- Tenant middleware isolation
- Typed API throttling and auth throttling
- OAuth social login (Google, GitHub, Microsoft) for existing active users
- Stripe recurring subscriptions with webhook lifecycle sync
- MercadoPago recurring subscriptions with webhook lifecycle sync
- Transactional email pipeline (SES/SendGrid/Postmark compatible) with provider metadata tracking
- Advanced observability for payments/subscriptions (SLO/SLA thresholds and alerts)
- Prometheus exporter for operational and revenue SLO/SLA metrics (`/api/metrics/prometheus`) with token + IP allowlist hardening
- Automated observability alerting (email + Slack) via scheduled command `iatechs:observability-alerts`
- Security headers middleware (CSP, HSTS, COOP/CORP, frame/clickjacking protection)
- Explicit CORS policy via `config/cors.php`

## External Observability (Prometheus/Grafana)

- Export endpoint: `GET /api/metrics/prometheus`
- Access control:
  - `OBS_EXPORTER_ENABLED=true`
  - `OBS_EXPORTER_TOKEN=<secure-token>`
  - `OBS_EXPORTER_ALLOWED_IPS=127.0.0.1,::1,<prometheus-ip-or-cidr>`
- Scrape auth:
  - Header `Authorization: Bearer <OBS_EXPORTER_TOKEN>`
  - or `X-Metrics-Token: <OBS_EXPORTER_TOKEN>`
- Alert dispatcher command:
  - `php artisan iatechs:observability-alerts --json`
  - Scheduler frequency controlled by `OBS_ALERTS_CHECK_INTERVAL_MINUTES`
- Alert channels:
  - Transactional email pipeline to active `super_admin` recipients
  - Slack webhook (`OBS_ALERTS_SLACK_WEBHOOK_URL`, fallback to `LOG_SLACK_WEBHOOK_URL`)
- Docker observability profile (Prometheus + Alertmanager + Grafana):
  - `cp docker/observability/secrets/obs_exporter_token.example docker/observability/secrets/obs_exporter_token`
  - `cp docker/observability/secrets/slack_webhook_url.example docker/observability/secrets/slack_webhook_url`
  - `docker compose --profile observability up -d prometheus alertmanager grafana`

## Deployment

- CI workflow: `.github/workflows/ci.yml`
- Security workflow: `.github/workflows/security.yml`
- Deploy workflow: `.github/workflows/deploy.yml` (includes `security-gates` + postdeploy observability checks)
- Operational runbooks: `docs/operations/*`
- Deployment scripts: `deploy/*`

## Canonical Documentation

Primary entrypoints:

- `docs/README.md`
- `docs/architecture/18-Canonical-Architecture-Source-Of-Truth.md`
- `docs/development/09-Technical-Implementation-Contract.md`
- `docs/operations/21-Project-Governance-Contract.md`
