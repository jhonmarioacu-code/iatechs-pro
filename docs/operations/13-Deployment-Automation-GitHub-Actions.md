# Deployment Automation (GitHub Actions)

## Objetivo
Definir el flujo oficial de despliegue automatizado para IAtechs Pro con validaciones previas, despliegue seguro, verificación de salud y rollback controlado.

## Alcance
- Entorno objetivo: `production`.
- Rama recomendada para deploy: `main`.
- Ejecución manual por operador autorizado: `workflow_dispatch`.

## Arquitectura del Pipeline
```text
workflow_dispatch
    |
    +--> precheck
    |      - validar rama
    |      - validar commit SHA
    |      - validar secretos requeridos
    |
    +--> deploy
    |      - backup .env y storage
    |      - pull de código
    |      - composer install --no-dev
    |      - migraciones --force
    |      - cache config/routes/views
    |      - restart php-fpm + reload nginx
    |
    +--> postcheck
           - curl /health
           - validación mínima de aplicación
           - rollback si health falla
```

## Requisitos de Seguridad
- Usar `GitHub Environments` para proteger producción.
- Requerir aprobación manual (`required reviewers`) para `production`.
- Prohibido almacenar secretos en repositorio.
- Registrar quién ejecuta cada despliegue y desde qué commit.

## Variables de Entrada del Workflow
- `target_env`: entorno (`production`).
- `deploy_ref`: rama o commit a desplegar (default `main`).
- `run_migrations`: `true|false` para migraciones controladas.

## Secretos Requeridos
- `SSH_HOST`
- `SSH_PORT`
- `SSH_USER`
- `SSH_PRIVATE_KEY`
- `DEPLOY_PATH` (ejemplo: `/var/www/iatechs-pro`)

## Flujo Operativo Recomendado
1. El operador ejecuta workflow manual.
2. `precheck` valida permisos y entradas.
3. `deploy` ejecuta comandos remotos con `set -euo pipefail`.
4. `postcheck` valida `/health`.
5. Si falla salud, ejecutar rollback automático al commit previo.
6. Publicar resumen del resultado (success/failure + timestamp + commit).

## Comandos Base Remotos (Referencia)
```bash
cd /var/www/iatechs-pro
git fetch --all --prune
git checkout main
git reset --hard origin/main
composer install --no-dev --optimize-autoloader
php artisan migrate --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.4-fpm
sudo systemctl reload nginx
curl -fsS http://127.0.0.1/health
```

## Estrategia de Rollback
- Guardar `PREV_SHA=$(git rev-parse HEAD)` antes de actualizar.
- Si falla deploy o `health`:
  - `git reset --hard "$PREV_SHA"`
  - `php artisan optimize:clear`
  - `php artisan config:cache && php artisan route:cache && php artisan view:cache`
  - reiniciar servicios
  - confirmar `/health`.

## Criterios de Éxito
- `/health` responde `200` con `status=ok`.
- Sin errores fatales en `storage/logs/laravel.log`.
- Flujo mínimo por rol validado.
- Registro de despliegue almacenado (commit, fecha, operador, resultado).

