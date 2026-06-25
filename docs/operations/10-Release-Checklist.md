  # Release Checklist (Production)

  ## 1. Predeploy
  - [ ] Confirmar rama/tag a desplegar.
  - [ ] Backup de `.env` y `storage/`.
  - [ ] Verificar variables críticas (`APP_ENV`, `APP_DEBUG=false`, DB, REDIS, QUEUE).
  - [ ] Validar permisos en `storage` y `bootstrap/cache`.
  - [ ] Confirmar que CI está en verde.

  ## 2. Deploy
  - [ ] `git pull origin main`
  - [ ] `composer install --no-dev --optimize-autoloader`
  - [ ] `php artisan migrate --force`
  - [ ] `php artisan optimize:clear`
  ## 3. Postdeploy
  - [ ] `curl -i http://127.0.0.1/health` devuelve `200`.
  - [ ] Login por portal funciona (admin/company/technician/customer).
  - [ ] Flujo crítico mínimo validado (ticket -> diagnóstico -> cotización -> reparación -> cierre).
  ## 4. Rollback
  - [ ] Restaurar backup de `.env` y `storage/` si aplica.
  - [ ] Volver al commit/tag anterior.
  - [ ] `php artisan migrate --force` (solo si rollback de código lo requiere).
  - [ ] Limpiar y regenerar cachés.
  - [ ] Validar `/health` en `200`.