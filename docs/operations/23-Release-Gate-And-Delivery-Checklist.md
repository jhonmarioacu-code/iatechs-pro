# Release Gate And Delivery Checklist

Fecha: 2026-06-26  
Estado: Oficial

## Objetivo

Convertir las reglas de entrega de IAtechsPro en validaciones ejecutables antes de marcar cualquier tarea como terminada.

## Comandos obligatorios

```bash
php artisan iatechs:audit-architecture
php artisan iatechs:gate-release
composer validate:release
```

## Instalacion limpia base

La base minima para levantar el proyecto desde cero sigue esta secuencia:

```bash
git clone <repo>
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run build
php artisan serve
```

Si un entorno necesita pasos extra, deben quedar automatizados en `deploy/*` y documentados en `docs/operations/*`.

## Validaciones del gate de release

El comando `iatechs:gate-release` valida:

1. Archivos minimos de release.
2. Scripts obligatorios de `composer.json`.
3. Contrato base de `.env.example`.
4. Valores esperados para stack oficial:
   `APP_ENV=production`, `APP_DEBUG=false`, `DB_CONNECTION=pgsql`,
   `CACHE_STORE=redis`, `SESSION_DRIVER=redis`, `QUEUE_CONNECTION=redis`.
5. Matriz de integraciones y sus variables de entorno:
   Azure OpenAI, Redis/Horizon, Mail, S3 (requeridas) y
   Reverb/Pusher (operativas, ver `docs/operations/24-Realtime-Broadcast-Runbook.md`)
   y Stripe/Mercado Pago/Cloudflare/OAuth-JWT-Passport (seguimiento).
   Observabilidad externa Prometheus/Grafana se documenta en `docs/operations/25-Observability-Prometheus-Grafana-Runbook.md`.
   Security scanning operativo se documenta en `docs/operations/26-Security-Scanning-Runbook.md`.

## Checklist de cierre obligatorio por tarea

Cada entrega debe incluir este bloque:

1. Estado.
2. Archivos creados.
3. Archivos modificados.
4. Migraciones.
5. Variables `.env` necesarias.
6. Dependencias nuevas.
7. Comandos necesarios.
8. Como probar.
9. Casos de prueba.
10. Riesgos encontrados.
11. Pendientes.
12. Compatibilidad:
    Laravel 12, PHP 8.4, DDD, Multi-Tenant, API v1, SPA, Docker, Azure OpenAI, Redis, Reverb, MySQL, Produccion.
