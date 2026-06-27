# IAtechs Pro - Release Status

Fecha de actualizacion: 2026-06-27

## Estado

Estado oficial actual: **Produccion final validada**.

El proyecto queda marcado como release-ready con evidencia operativa de despliegue automatizado, rollback y validacion estricta de observabilidad.

## Baseline validado

- Backend Laravel 12 + PHP 8.4 + PostgreSQL/Redis orientado a produccion.
- Portales por rol (`admin`, `company`, `technician`, `customer`).
- API v1 con aislamiento tenant y capas DDD operativas.
- Quality gates de seguridad y dependencias en pipeline de deploy.
- Deploy reproducible en `main` con rollback automatizado.
- Stack de observabilidad externo operativo:
  - Prometheus
  - Alertmanager
  - Grafana
- Verificacion postdeploy estricta (`validate_prometheus_stack=true`) en verde.

## Gates de salida cumplidos

1. Validaciones de seguridad en CI/CD para despliegue (`security-gates`) en verde.
2. Build frontend y `composer install` en servidor objetivo en verde.
3. Healthcheck de aplicacion (`/health`) en verde postdeploy.
4. Verificacion de observabilidad (`/api/metrics/prometheus`, Prometheus target, Alertmanager, Grafana) en verde.
5. Runbooks y evidencias de release actualizados en `docs/operations`.

## Archivos operativos clave

- `DEPLOYMENT.md`
- `.github/workflows/deploy.yml`
- `deploy/observability-postdeploy-check.sh`
- `docs/operations/13-Deployment-Automation-GitHub-Actions.md`
- `docs/operations/25-Observability-Prometheus-Grafana-Runbook.md`
- `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`
