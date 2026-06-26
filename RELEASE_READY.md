# IAtechs Pro - Release Status

Fecha de actualizacion: 2026-06-25

## Estado

Estado oficial actual: **Preproduccion tecnica (beta avanzada)**.

El proyecto tiene base enterprise operativa (arquitectura, dominios, seguridad, multi-tenant, CI/CD, runbooks), pero aun no se marca como "produccion final" hasta cerrar los gates de release definidos.

## Baseline validado

- Backend Laravel 12 + PHP 8.4 + PostgreSQL/Redis orientado a produccion.
- Portales por rol (`admin`, `company`, `technician`, `customer`).
- API v1 con aislamiento tenant y capas DDD operativas.
- Pruebas funcionales y de seguridad en verde: `69 passed (450 assertions)`.
- Pipeline de calidad estatico en verde: `composer analyse`.
- Validacion de cache de configuracion/rutas en verde via `composer validate:testing`.
- Recuperacion de contrasena con throttling y tests de seguridad en verde.

## Gates para marcar produccion final

1. `composer analyse` en verde en CI (alcance oficial de release).
2. Build frontend validado en servidor objetivo.
3. Deploy + migrate + health + smoke tests con evidencia de postdeploy.
4. Checklist de release completo en `docs/operations/10-Release-Checklist.md`.

## Archivos operativos clave

- `DEPLOYMENT.md`
- `deploy/aws-ec2-production-setup.sh`
- `.github/workflows/ci.yml`
- `.github/workflows/deploy.yml`
- `docs/operations/10-Release-Checklist.md`
