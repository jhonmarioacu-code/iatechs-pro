# Release Checklist (Production)

Fecha de actualizacion: 2026-06-25

## 1. Predeploy

- [ ] Confirmar branch/tag de release.
- [ ] Confirmar CI en verde (`composer analyse`, `composer test`, frontend build).
- [ ] Backup de `.env` y `storage/`.
- [ ] Verificar variables criticas (`APP_ENV=production`, `APP_DEBUG=false`, DB, REDIS, QUEUE).
- [ ] Verificar permisos en `storage/` y `bootstrap/cache/`.
- [ ] Verificar secretos de despliegue en GitHub Environments.

## 2. Deploy

- [ ] `git fetch --all --prune`
- [ ] `git checkout <ref-release>`
- [ ] `composer install --no-dev --optimize-autoloader`
- [ ] `npm ci`
- [ ] `npm run build`
- [ ] `php artisan migrate --force`
- [ ] `php artisan optimize:clear`
- [ ] `php artisan config:cache`
- [ ] `php artisan route:cache`
- [ ] `php artisan view:cache`

## 3. Postdeploy

- [ ] `curl -fsS http://127.0.0.1/health` responde `200`.
- [ ] `curl -fsS https://<dominio>/health` responde `200`.
- [ ] Login por portal funciona (`admin/company/technician/customer`).
- [ ] Flujo critico validado: `ticket -> diagnostico -> cotizacion -> reparacion -> cierre`.
- [ ] Horizon y workers operativos (`php artisan horizon:status` / supervisor).

## 4. Rollback

- [ ] Volver al commit/tag anterior.
- [ ] Restaurar backup de `.env` y `storage/` si aplica.
- [ ] Limpiar/regenerar cache.
- [ ] Reiniciar PHP-FPM, Nginx y workers.
- [ ] Validar `/health` en `200`.

## 5. Evidencia obligatoria de release

- [ ] SHA desplegado.
- [ ] Resultado de CI.
- [ ] Resultado de migraciones.
- [ ] Evidencia de health checks.
- [ ] Evidencia de smoke tests por portal.

