# Technical Implementation Contract

Fecha: 2026-06-26  
Estado: Oficial

## 1. Objetivo

Definir como implementar funcionalidades nuevas en IAtechsPro respetando arquitectura enterprise multi-tenant y stack oficial.

## 2. Stack de implementacion

- PHP 8.4+
- Laravel 12
- PostgreSQL como motor oficial
- Redis para cache, colas, horizon, sesiones y broadcasting
- Blade + Vite + TailwindCSS + Alpine.js

## 3. Estructura obligatoria por dominio

Toda logica de negocio vive en `app/Domains/*`.

Estructura recomendada por dominio:

- Models
- Controllers
- Requests
- Resources
- Services
- Actions
- Repositories
- Interfaces
- DTOs
- Policies
- Events
- Listeners
- Jobs
- Exceptions
- Enums
- Traits
- Tests

## 4. Reglas de implementacion

1. Controllers solo orquestan; no contienen reglas de negocio.
2. Requests validan entrada.
3. Policies autorizan acceso.
4. Services/Actions encapsulan casos de uso.
5. Repositories encapsulan acceso a datos.
6. Resources estandarizan respuestas.
7. Eventos y jobs desacoplan procesos pesados.

## 5. Contrato de API

- API REST versionada por `v1`.
- Rutas bajo `routes/api/v1/*`.
- Prefijo de version en `routes/api.php`.
- Documentar contratos en `docs/api/*`.
- Mantener compatibilidad retroactiva dentro de la misma version.

## 6. Contrato de datos

1. Toda tabla operativa incluye `company_id`.
2. Indexar `company_id` y combinaciones de alta frecuencia.
3. Usar constraints y llaves foraneas para integridad.
4. Disenar migraciones para PostgreSQL (tipos, indices y consultas compatibles).
5. No introducir dependencias funcionales a MySQL como motor objetivo.

## 7. Contrato de tenancy

- Resolver tenant antes de ejecutar reglas de negocio.
- Forzar filtros tenant en lectura y escritura.
- Validar pertenencia tenant en policies.
- Propagar `company_id` en eventos/jobs.

## 8. Contrato de infraestructura

- Cache: Redis namespaced por tenant cuando aplique.
- Queue: Redis + Horizon.
- Scheduler: cron + `php artisan schedule:run`.
- Workers: Supervisor.

## 9. Pruebas y calidad minima

Antes de merge/release:

- `composer analyse`
- `composer test`
- `composer validate:testing`
- `npm run build`

Si hay cambios de seguridad/dependencias:

- `composer audit`

## 10. Documentacion obligatoria por cambio

Todo PR que altere comportamiento de negocio debe actualizar:

1. Documento de modulo impactado (`docs/modules*`)
2. Documento tecnico impactado (`docs/architecture` o `docs/development`)
3. Decision tecnica (`docs/decisions`) si cambia stack o direccion

