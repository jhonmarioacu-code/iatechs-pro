# IAtechsPro Enterprise Architecture Charter

Fecha: 2026-06-25
Estado: Oficial (obligatorio para todo desarrollo nuevo)

## 1. Objetivo

Estandarizar como se analiza, diseña, implementa y audita IAtechsPro para mantener una arquitectura SaaS Enterprise multi-tenant, escalable y mantenible a largo plazo.

## 2. Stack oficial y restricciones

- Framework: Laravel 12, PHP 8.4
- DB: PostgreSQL
- Cache/colas: Redis + Horizon
- Auth API: Sanctum
- Roles/Permisos: Spatie Permission
- Infra base: Ubuntu 24.04, Nginx, Supervisor
- Frontend: Blade + Vite + TailwindCSS + AlpineJS

Restriccion: No introducir tecnologia que rompa este baseline sin ADR aprobado.

## 3. Principios obligatorios

- DDD por dominio (`app/Domains/*`)
- Service Layer + Repository Pattern
- SOLID + Clean Code
- Policies para autorizacion
- Form Requests para validacion
- API Resources para salida estandarizada
- DTOs en fronteras de aplicacion
- Eventos y Jobs para procesos asincronos/pesados
- Multi-tenant estricto por `company_id`

## 4. Estructura de dominio obligatoria

Ruta base:

```text
app/
+-- Domains/
```

Cada dominio debe usar:

```text
DomainName/
+-- Controllers
+-- Models
+-- Repositories
+-- Services
+-- Requests
+-- Resources
+-- Policies
+-- Events
+-- Listeners
+-- Jobs
+-- DTOs
+-- Actions
+-- Exceptions
```

Regla: no crear logica de negocio nueva fuera de esta estructura.

## 5. Contrato multi-tenant (no negociable)

Componentes base:

- `TenantResolver`
- `TenantManager`
- `TenantMiddleware`
- `CompanyScope`
- `BelongsToCompany`

Reglas:

1. Toda entidad operativa debe tener `company_id`.
2. Toda consulta en repositorio debe quedar aislada por tenant.
3. Toda creacion debe asignar `company_id` del contexto resuelto.
4. Toda autorizacion debe validar pertenencia tenant + permiso/policy.
5. Cero acceso cross-tenant en UI, API o jobs.

## 6. Flujo obligatorio antes de programar

### Fase 1 - Analisis

- Objetivo funcional y de negocio.
- Usuario/rol impactado.
- Flujo end-to-end y casos de uso.
- Reglas de negocio y criterios de aceptacion.
- Riesgos de seguridad y multi-tenant.
- Dependencias tecnicas.

### Fase 2 - Arquitectura

- Dominio y agregados impactados.
- Entidades/relaciones y cambios de datos.
- Casos de uso (application services/actions).
- Policies, Requests, Resources.
- Eventos y jobs.
- Impacto en performance/escalabilidad.

### Fase 3 - Implementacion

Entregables minimos por modulo:

- Migracion
- Modelo
- Repository
- Service/Action
- Requests
- Resources
- Policy
- Controller
- Routes
- Tests (unit, feature, seguridad/tenant)

### Fase 4 - Auditoria

Checklist de cierre:

- Cumple DDD y estructura oficial.
- Cumple aislamiento tenant en lectura/escritura/autorizacion.
- Compatible con PostgreSQL y Laravel 12.
- Seguridad: validacion, policy, permisos, sanitizacion.
- Performance: indices, N+1, cache, jobs.
- Escalabilidad: sin acoplamientos innecesarios.

## 7. Definition of Done Enterprise

Una funcionalidad queda "Done" solo si:

1. Tiene trazabilidad de Fase 1 a Fase 4.
2. Incluye tests verdes de comportamiento y aislamiento tenant.
3. No introduce deuda critica de arquitectura.
4. Mantiene naming, tipado estricto y convenciones del proyecto.
5. Documenta cambios de contratos API, reglas o flujos.

## 8. Reglas de calidad permanentes

Nunca:

- Duplicar logica de negocio.
- Escribir consultas operativas sin `company_id`.
- Saltar policies o validaciones.
- Dejar procesos pesados sin cola/job.
- Mezclar reglas de dominio en controllers/views.

Siempre:

- Tipado estricto y contratos claros.
- Transacciones en cambios multi-entidad.
- Validaciones en Request y autorizacion en Policy.
- Recursos de salida consistentes.
- Eventos para desacoplar efectos secundarios.

## 9. Auditoria permanente de codigo

Ante cualquier cambio se revisa:

1. Arquitectura y acoplamiento.
2. Riesgos funcionales.
3. Riesgos multi-tenant.
4. Riesgos de seguridad.
5. Riesgos de performance.
6. Riesgos de escalabilidad.

Resultado esperado de auditoria:

- Hallazgos por severidad
- Evidencia (archivo/linea)
- Recomendacion concreta
- Plan de correccion

## 10. Mapa oficial de dominios

Core SaaS:

- Companies, Branches, Users, Roles, Permissions, Plans, Subscriptions, Billings

CRM:

- Customers, Contacts, Leads

Service Desk:

- Tickets, Devices, Repairs, Diagnostics, WorkOrders

Inventory:

- Products, Categories, Warehouses, StockMovements, Suppliers

Financial:

- Invoices, InvoiceItems, Payments, Expenses, Accounting

Knowledge Base:

- Articles, Documents

AI:

- AIAssistant, Predictions, Recommendations

Reports:

- Dashboards, Reports, KPIs

## 11. Criterio de decision arquitectonica

Si existen varias opciones, seleccionar la opcion con mejor balance de:

1. Aislamiento tenant y seguridad
2. Escalabilidad horizontal
3. Mantenibilidad del dominio
4. Testabilidad y observabilidad
5. Costo operativo sostenible
