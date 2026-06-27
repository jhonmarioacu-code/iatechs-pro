# Deploy Workflow Runbook

## Objetivo

Explicar como ejecutar el despliegue automatizado desde GitHub Actions de forma segura y reproducible.

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
- Secrets de observabilidad cuando aplica:
  - `OBS_EXPORTER_TOKEN`
  - `OBS_ALERTS_SLACK_WEBHOOK_URL`
- Servidor accesible por SSH para el usuario de despliegue.
- Docker y Docker Compose instalados en servidor.
- Usuario de despliegue con acceso a Docker (`docker` group o `sudo -n docker`).

## Ejecucion recomendada (2 pasos)

### Paso 1: despliegue con provision y checks basicos

1. Ir a `GitHub > Actions > Deploy`.
2. Clic en `Run workflow`.
3. Seleccionar:
   - `target_env=production`
   - `deploy_ref=main`
   - `run_migrations=true`
   - `run_observability_checks=true`
   - `provision_observability_stack=true`
   - `validate_prometheus_stack=false`
4. Confirmar ejecucion.

### Paso 2: validacion estricta de observabilidad

1. Ejecutar nuevamente `Run workflow`.
2. Mantener inputs iguales y cambiar:
   - `validate_prometheus_stack=true`
3. Confirmar ejecucion.

## Que hace el pipeline

1. `security-gates`
   - `composer audit --locked`
   - `npm audit --omit=dev --audit-level=high`
2. `precheck`
   - valida inputs y secretos
3. `deploy`
   - backup de `.env` y `storage`
   - `git fetch/checkout/pull`
   - provision de secretos/variables de observabilidad (si aplica)
   - `docker compose --profile observability up -d --no-deps prometheus alertmanager grafana` (si aplica)
   - `composer install --no-dev`
   - `npm ci && npm run build`
   - `php artisan migrate --force` (si aplica)
   - `optimize:clear`, `config:cache`, `route:cache`, `view:cache`
   - restart php-fpm + reload nginx
   - `curl /health`
   - postdeploy checks de observabilidad (si aplica)
4. `postcheck`
   - confirma nuevamente `/health`

## Resultado esperado

- `security-gates`: success
- `precheck`: success
- `deploy`: success
- `postcheck`: success
- `http://127.0.0.1/health`: `200`
- Si `validate_prometheus_stack=true`:
  - Prometheus healthy
  - target `iatechs_app` en `UP`
  - Alertmanager healthy
  - Grafana healthy

## Buenas practicas

- Ejecutar fuera de horas pico cuando hay migraciones.
- Usar `deploy_ref` por SHA para despliegues trazables.
- Registrar en bitacora: operador, fecha, commit, run URL y resultado.
- Si falla modo estricto, corregir primero infraestructura Docker y reintentar.
