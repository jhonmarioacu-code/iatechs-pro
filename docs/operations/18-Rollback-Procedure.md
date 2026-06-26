# Rollback Procedure

## Objetivo
Restaurar servicio rápidamente cuando un despliegue falla o degrada operación crítica.

## Disparadores de rollback
- `/health` no responde `200`.
- Error 500 recurrente después de deploy.
- Flujos críticos por rol inoperables.
- Errores fatales continuos en `laravel.log`.

## Rollback automático
El workflow `deploy.yml` incluye rollback automático en el job `deploy`:
- Captura `PREV_SHA` antes de actualizar código.
- Ante error, ejecuta:
  - `git reset --hard "$PREV_SHA"`
  - recache de Laravel
  - restart de servicios
  - verificación de `/health`

## Rollback manual (servidor)
```bash
cd /var/www/iatechs-pro
PREV_SHA="<sha_anterior_estable>"
git reset --hard "$PREV_SHA"
composer install --no-dev --optimize-autoloader --no-interaction
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
sudo systemctl restart php8.4-fpm
sudo systemctl reload nginx
curl -i http://127.0.0.1/health
```

## Restauración de respaldos
Si el incidente involucra configuración/archivos:
```bash
sudo cp /tmp/iatechs-pro.env.<timestamp>.bak /var/www/iatechs-pro/.env
sudo tar -xzf /tmp/iatechs-pro.storage.<timestamp>.bak.tgz -C /
```

## Verificación posterior al rollback
- `/health` en `200`.
- Login por roles funcional.
- Flujo mínimo ticket->diagnóstico->cotización->reparación->cierre funcional.
- Sin errores críticos nuevos en `storage/logs/laravel.log`.

## Registro de incidente
- Fecha/hora:
- Operador:
- Commit fallido:
- Commit restaurado:
- Causa raíz:
- Correctivo permanente:
- Estado final:

