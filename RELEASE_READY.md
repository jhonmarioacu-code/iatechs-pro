# IAtechs Pro - Release Ready

## Estado

Este repositorio queda preparado para subida y despliegue con:

- Frontend Blade + Tailwind + Alpine estructurado por portales.
- Build de Vite para assets de producción.
- Pipeline CI con análisis, pruebas y build.
- Checklist de validación pre y post deploy.

## Frontend implementado

- Public Website: `/`
- Admin Portal: `/portal/admin`
- Company Portal: `/portal/company`
- Technician Portal: `/portal/technician`
- Customer Portal: `/portal/customer`
- Módulos por portal (placeholder navegable): `/portal/{portal}/{module}`

## Checklist antes de subir

1. Ejecutar `composer analyse`.
2. Ejecutar `composer test`.
3. Ejecutar `npm ci`.
4. Ejecutar `npm run build`.
5. Validar rutas con `php artisan route:list`.

## Checklist antes de producción

1. Configurar `.env` real de producción (sin secretos en git).
2. Ejecutar `composer install --no-dev --optimize-autoloader`.
3. Ejecutar `npm ci && npm run build`.
4. Ejecutar `php artisan migrate --force`.
5. Ejecutar:
   - `php artisan optimize`
   - `php artisan config:cache`
   - `php artisan route:cache`
   - `php artisan view:cache`
6. Reiniciar workers:
   - `php artisan horizon:terminate`
   - `php artisan queue:restart`
7. Verificar salud:
   - `/health`
   - `/api/health`
   - login/portal principal

## Archivos clave de despliegue

- `DEPLOYMENT.md`
- `deploy/aws-ec2-production-setup.sh`
- `.github/workflows/ci.yml`

