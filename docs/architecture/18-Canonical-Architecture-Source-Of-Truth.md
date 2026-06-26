# Canonical Architecture Source of Truth

Fecha: 2026-06-26  
Estado: Oficial y obligatorio

## 1. Definicion de producto

IAtechsPro es una plataforma SaaS Enterprise Multi-Tenant para empresas de servicios tecnicos.  
No se define como un sistema aislado de tickets.

La arquitectura debe soportar operacion integral de negocio:

- Core SaaS (companies, branches, users, roles, permissions, plans, subscriptions, billing)
- CRM (customers, contacts, leads)
- Soporte tecnico (tickets, devices, diagnostics, repairs, work orders, technicians)
- Inventario y compras
- Comercial y facturacion
- Reportes, auditoria, notificaciones y settings
- IA y automatizaciones

## 2. Stack oficial

- PHP 8.4+
- Laravel 12
- PostgreSQL (motor oficial de datos)
- Redis (cache, queues, horizon, sessions, broadcasting)
- Nginx + PHP-FPM + Supervisor
- Ubuntu 24.04
- Blade + Vite + TailwindCSS + Alpine.js (fase actual)

## 3. Principios arquitectonicos obligatorios

- DDD
- Clean Architecture
- SOLID
- Repository Pattern
- Service Layer
- Actions
- DTOs
- Policies
- Requests
- API Resources
- Events, Listeners, Jobs, Notifications

## 4. Ubicacion de logica de negocio

Toda logica de negocio nueva vive en:

```text
app/Domains/
```

No se permite crear logica de negocio fuera del dominio.

Cada dominio debe contemplar, segun el caso:

- Models
- Controllers
- Requests
- Resources
- Services
- Actions
- Repositories
- Interfaces
- DTOs
- Events
- Listeners
- Jobs
- Policies
- Exceptions
- Enums
- Traits
- Tests

## 5. Contrato Multi-Tenant

Todo el sistema gira sobre la empresa (`company_id`) y debe evitar mezcla de datos entre tenants.

Infraestructura base obligatoria:

- TenantResolver
- TenantManager
- CompanyScope
- BelongsToCompany
- TenantMiddleware

Reglas:

1. Toda entidad operativa usa `company_id`.
2. Toda consulta de negocio queda filtrada por tenant.
3. Toda escritura asigna `company_id` del tenant activo.
4. Toda autorizacion valida tenant + permiso/policy.
5. Ningun proceso UI/API/Job puede exponer acceso cross-tenant.

## 6. Contrato de API

- API REST versionada
- Rutas en `routes/api/v1/*`
- Requests para validacion
- Policies para autorizacion
- Resources para salida estable
- OpenAPI/Swagger como contrato publico evolutivo

## 7. Contrato de IA

El modulo de IA es estrategico y debe incluir:

- Chat IA
- Diagnostico automatico
- Generacion de soluciones
- Respuestas inteligentes
- Base de conocimiento
- Automatizaciones y agentes IA

Integracion prevista:

- Azure AI Foundry
- Azure OpenAI

## 8. Restricciones de arquitectura

No se permite:

- Logica de negocio en controllers o views
- Crear tablas/relaciones sin diseno previo
- Saltar validaciones de Request o autorizacion de Policy
- Bypassear aislamiento tenant
- Introducir stack fuera del baseline sin ADR aprobada

## 9. Referencias canonicas

- `docs/README.md`
- `docs/architecture/16-Enterprise-Architecture-Charter.md`
- `docs/development/09-Technical-Implementation-Contract.md`
- `docs/modules/00-Business-Domain-Map.md`
- `docs/operations/21-Project-Governance-Contract.md`

