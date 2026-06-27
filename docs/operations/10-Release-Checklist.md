# Release Checklist (Production)

Fecha de actualizacion: 2026-06-27  
Estado: Cerrado (release aprobado)

## Registro de release aplicado

- Ref de release: `main`
- Tag base de salida: `v1.0.0` (`e93da55e`)
- Estado actual en produccion: merge de PR #18 (`d83207b9`) con dashboard premium sobre base productiva estable.
- Evidencia principal: `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`

## 1. Predeploy

- [x] Confirmar branch/tag de release.
- [x] Confirmar CI en verde (`composer analyse`, `composer test`, frontend build).
- [x] Backup de `.env` y `storage/`.
- [x] Verificar variables criticas (`APP_ENV=production`, `APP_DEBUG=false`, DB, REDIS, QUEUE).
- [x] Verificar permisos en `storage/` y `bootstrap/cache/`.
- [x] Verificar secretos de despliegue en GitHub Environments.

## 2. Deploy

- [x] `git fetch --all --prune`
- [x] `git checkout <ref-release>`
- [x] `composer install --no-dev --optimize-autoloader`
- [x] `npm ci`
- [x] `npm run build`
- [x] `php artisan migrate --force`
- [x] `php artisan optimize:clear`
- [x] `php artisan config:cache`
- [x] `php artisan route:cache`
- [x] `php artisan view:cache`

## 3. Postdeploy

- [x] `curl -fsS http://127.0.0.1/health` responde `200`.
- [x] `HEALTHCHECK_URL` validada en `postcheck` del workflow.
- [x] Login por portal funciona (`admin/company/technician/customer`) con smoke tests de seguridad en verde.
- [x] Flujo critico validado: `ticket -> diagnostico -> cotizacion -> reparacion -> cierre`.
- [x] Horizon/workers y cola operativos en verificaciones de salud de aplicacion y runtime.

## 4. Rollback

- [x] Volver al commit/tag anterior.
- [x] Restaurar backup de `.env` y `storage/` si aplica.
- [x] Limpiar/regenerar cache.
- [x] Reiniciar PHP-FPM, Nginx y workers.
- [x] Validar `/health` en `200`.

## 5. Evidencia obligatoria de release

- [x] SHA desplegado.
- [x] Resultado de CI.
- [x] Resultado de migraciones.
- [x] Evidencia de health checks.
- [x] Evidencia de smoke tests por portal.

## Evidencia consolidada

- `RELEASE_READY.md`
- `docs/operations/12-Post-Deploy-Verification.md`
- `docs/operations/13-Deployment-Automation-GitHub-Actions.md`
- `docs/operations/17-Deploy-Workflow-Runbook.md`
- `docs/operations/25-Observability-Prometheus-Grafana-Runbook.md`
- `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`

