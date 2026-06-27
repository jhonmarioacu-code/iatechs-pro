# Deployment Automation (GitHub Actions)

## Objetivo

Definir el flujo oficial de despliegue automatizado para IAtechs Pro con validaciones previas, despliegue seguro, observabilidad y rollback controlado.

## Alcance

- Entorno objetivo: `production`.
- Rama recomendada para deploy: `main`.
- Ejecucion manual por operador autorizado: `workflow_dispatch`.

## Arquitectura del pipeline

```text
workflow_dispatch
    |
    +--> security-gates
    |      - composer audit --locked
    |      - npm audit --omit=dev --audit-level=high
    |
    +--> precheck
    |      - validar entorno y secretos
    |
    +--> deploy
    |      - backup .env y storage
    |      - pull de codigo
    |      - preflight de permisos runtime
    |      - (opcional) provision observabilidad desde GitHub secrets
    |      - (opcional) docker compose profile observability (--no-deps)
    |      - composer install --no-dev
    |      - npm ci + npm run build
    |      - migraciones --force
    |      - cache config/routes/views
    |      - restart php-fpm + reload nginx
    |      - health check
    |      - (opcional) observability postdeploy checks
    |
    +--> postcheck
           - curl /health
```

## Inputs del workflow `Deploy`

- `target_env`: entorno (`production`).
- `deploy_ref`: rama o commit a desplegar (default `main`).
- `run_migrations`: `true|false`.
- `php_fpm_service`: servicio PHP-FPM.
- `nginx_service`: servicio Nginx.
- `run_observability_checks`: ejecuta smoke checks de observabilidad postdeploy.
- `validate_prometheus_stack`: exige validacion completa de Prometheus/Alertmanager/Grafana.
- `provision_observability_stack`: crea secretos/variables y levanta profile Docker `observability`.

## Secrets requeridos

Base:

- `SSH_HOST`
- `SSH_PORT`
- `SSH_USER`
- `SSH_PRIVATE_KEY`
- `DEPLOY_PATH`

Observabilidad automatizada (si `provision_observability_stack=true`):

- `OBS_EXPORTER_TOKEN`
- `OBS_ALERTS_SLACK_WEBHOOK_URL`

Opcionales recomendados:

- `OBS_EXPORTER_ALLOWED_IPS`
- `PROMETHEUS_RETENTION_TIME`
- `GRAFANA_ADMIN_USER`
- `GRAFANA_ADMIN_PASSWORD`

## Requisitos de seguridad

- Usar `GitHub Environments` para proteger produccion.
- Requerir aprobacion manual (`required reviewers`) para `production`.
- Prohibido almacenar secretos en repositorio.
- Registrar quien ejecuta cada despliegue y desde que commit.

## Requisitos de infraestructura para observabilidad estricta

- Docker instalado en host.
- Docker Compose plugin disponible.
- Usuario de deploy con acceso a Docker (`docker` group o `sudo -n docker`).

## Flujo operativo recomendado

1. Ejecutar `Deploy` con:
   - `run_observability_checks=true`
   - `provision_observability_stack=true`
   - `validate_prometheus_stack=false`
2. Confirmar jobs en verde.
3. Ejecutar segunda corrida con:
   - `validate_prometheus_stack=true`
4. Confirmar jobs en verde y registrar evidencia.

## Estrategia de rollback

- Guardar `PREV_SHA=$(git rev-parse HEAD)` antes de actualizar.
- Si falla deploy o health:
  - normalizar permisos runtime
  - `git reset --hard "$PREV_SHA"`
  - `php artisan optimize:clear`
  - `php artisan config:cache && php artisan route:cache && php artisan view:cache`
  - reiniciar servicios
  - validar `/health`

## Criterios de exito

- `/health` responde `200` con `status=ok`.
- Sin errores fatales repetitivos en `storage/logs/laravel.log`.
- `security-gates`, `precheck`, `deploy`, `postcheck` en verde.
- Si aplica modo estricto, observabilidad postdeploy en verde.
