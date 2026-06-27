# Deployment Automation (GitHub Actions)

## Objetivo
Definir el flujo oficial de despliegue automatizado para IAtechs Pro con validaciones previas, despliegue seguro, verificacion de salud, observabilidad y rollback controlado.

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
    |      - (opcional) provision observabilidad desde GitHub secrets
    |      - composer install --no-dev
    |      - migraciones --force
    |      - cache config/routes/views
    |      - restart php-fpm + reload nginx
    |      - (opcional) run observability postdeploy checks
    |
    +--> postcheck
           - curl /health
           - validacion minima final
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

## Flujo operativo recomendado
1. Ejecutar workflow `Deploy` (ya incluye `security-gates`).
2. Usar `run_observability_checks=true`.
3. Si ya existe stack observabilidad en host, ejecutar con `validate_prometheus_stack=true`.
4. Validar `security-gates`, `precheck`, `deploy`, `postcheck` en verde.

## Estrategia de rollback
- Guardar `PREV_SHA=$(git rev-parse HEAD)` antes de actualizar.
- Si falla deploy o health:
  - `git reset --hard "$PREV_SHA"`
  - `php artisan optimize:clear`
  - `php artisan config:cache && php artisan route:cache && php artisan view:cache`
  - reiniciar servicios
  - confirmar `/health`.

## Criterios de exito
- `/health` responde `200` con `status=ok`.
- Sin errores fatales en `storage/logs/laravel.log`.
- Flujo minimo por rol validado.
- `security-gates` del deploy en verde.
- Si aplica, observabilidad postdeploy en verde.
