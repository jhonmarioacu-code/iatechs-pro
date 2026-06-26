# Deploy Workflow Runbook

## Objetivo
Explicar cómo ejecutar el despliegue automatizado desde GitHub Actions de forma segura.

## Archivo de referencia
- `.github/workflows/deploy.yml`

## Requisitos previos
- PR mergeado a `main`.
- Environment `production` creado en GitHub.
- Secrets cargados en `production`:
  - `SSH_HOST`
  - `SSH_PORT`
  - `SSH_USER`
  - `SSH_PRIVATE_KEY`
  - `DEPLOY_PATH`
- Servidor accesible por SSH para el usuario de despliegue.

## Ejecución manual (workflow_dispatch)
1. Ir a `GitHub > Actions > Deploy`.
2. Clic en `Run workflow`.
3. Seleccionar:
   - `target_env`: `production`
   - `deploy_ref`: `main` (o SHA puntual)
   - `run_migrations`: `true`/`false`
   - `php_fpm_service`: por defecto `php8.4-fpm`
   - `nginx_service`: por defecto `nginx`
4. Confirmar ejecución.

## Qué hace el pipeline
1. `precheck`
   - valida inputs
   - valida secretos requeridos
2. `deploy`
   - backup de `.env` y `storage`
   - `git fetch/checkout/pull`
   - `composer install --no-dev`
   - `npm ci && npm run build`
   - `php artisan migrate --force` (si aplica)
   - `optimize:clear`, `config:cache`, `route:cache`, `view:cache`
   - corrige permisos `storage/bootstrap/cache`
   - reinicia `php-fpm` y recarga `nginx`
   - valida `curl /health`
3. `postcheck`
   - confirma nuevamente `/health`

## Resultado esperado
- Job `precheck`: success
- Job `deploy`: success
- Job `postcheck`: success
- Endpoint `http://127.0.0.1/health`: `200`

## Buenas prácticas
- Ejecutar fuera de horas pico cuando hay migraciones.
- Usar `deploy_ref` por SHA para despliegues trazables.
- Guardar en bitácora: operador, fecha, commit, resultado.

