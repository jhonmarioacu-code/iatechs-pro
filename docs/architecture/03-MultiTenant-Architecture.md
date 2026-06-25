# IAtechs Pro

# Architecture

## 03-MultiTenant-Architecture.md

---

# Objetivo

Definir la arquitectura Multi-Tenant oficial de IAtechs Pro para garantizar aislamiento de datos, seguridad empresarial, escalabilidad y administración centralizada de múltiples empresas dentro de una única plataforma SaaS.

---

# Estrategia Multi-Tenant

## Modelo Seleccionado

```text
Shared Database
Shared Schema
Tenant Isolation by company_id
```

---

# Justificación

Ventajas:

* Menor costo operativo.
* Menor complejidad.
* Escalabilidad horizontal.
* Fácil mantenimiento.
* Compatible con PostgreSQL.
* Compatible con AWS RDS.
* Compatible con Laravel 12.
* Compatible con arquitectura SaaS Enterprise.

---

# Arquitectura General

```text
IAtechs Pro SaaS

Empresa A
Empresa B
Empresa C
Empresa D

        ↓

PostgreSQL

companies
users
products
tickets
customers
invoices
payments

(company_id)
```

---

# Tenant Principal

## Company

Entidad raíz del sistema.

Tabla:

```text
companies
```

---

# Regla Fundamental

```text
Ningún usuario podrá acceder a datos de otra empresa.
```

---

# Company ID

Toda entidad empresarial deberá contener:

```sql
company_id
```

---

# Componentes del Tenant Core

## TenantResolverInterface

Ubicación:

```text
app/Tenant/Contracts/TenantResolverInterface.php
```

Responsabilidad:

* Definir el contrato para resolver el tenant actual.

---

## TenantResolver

Ubicación:

```text
app/Tenant/Services/TenantResolver.php
```

Responsabilidad:

* Resolver la empresa asociada al usuario autenticado.

---

## TenantManager

Ubicación:

```text
app/Tenant/Services/TenantManager.php
```

Responsabilidades:

* Registrar tenant activo.
* Obtener tenant actual.
* Obtener company_id.
* Limpiar tenant activo.

---

## TenantMiddleware

Ubicación:

```text
app/Tenant/Middleware/TenantMiddleware.php
```

Responsabilidades:

* Resolver tenant.
* Registrar contexto tenant.
* Inicializar entorno empresarial.
* Bloquear accesos cruzados.

---

## CompanyScope

Ubicación:

```text
app/Tenant/Scopes/CompanyScope.php
```

Responsabilidad:

Aplicar automáticamente:

```sql
WHERE company_id = ?
```

en todas las consultas empresariales.

---

## BelongsToCompany

Ubicación:

```text
app/Traits/BelongsToCompany.php
```

Responsabilidades:

* Registrar CompanyScope.
* Asignar company_id automáticamente.
* Definir relación Company.

---

# Flujo General

```text
Login
   ↓
User
   ↓
Company
   ↓
TenantResolver
   ↓
TenantManager
   ↓
TenantMiddleware
   ↓
CompanyScope
   ↓
Modelos
```

---

# Modelos Compatibles

## ERP

```text
Products
Suppliers
PurchaseOrders
GoodsReceipts
```

---

## Service Desk

```text
Customers
Devices
Tickets
```

---

## Billing

```text
Invoices
Payments
```

---

## CRM

```text
Leads
Opportunities
```

---

## Accounting

```text
Accounts
JournalEntries
```

---

## Knowledge Base

```text
KnowledgeArticles
```

---

## Reports

```text
Reports
```

---

## Notifications

```text
Notifications
```

---

# Excepciones

No utilizar CompanyScope en:

```text
Companies
Plans
Subscriptions
Roles Globales
Permisos Globales
Super Admin Modules
```

---

# Roles Multi-Tenant

## SaaS Super Admin

```text
super_admin
```

Acceso global.

---

## Company Owner

```text
owner
```

Acceso total a su empresa.

---

## Manager

```text
manager
```

Acceso administrativo interno.

---

## Technician

```text
technician
```

Acceso operativo.

---

## Customer

```text
customer
```

Acceso limitado a su información.

---

# Aislamiento de Datos

## Base de Datos

```sql
company_id
```

---

## Aplicación

```text
TenantMiddleware
CompanyScope
Policies
Repositories
Services
```

---

## Frontend

```text
Dashboard filtrado
Menús filtrados
Reportes filtrados
Widgets filtrados
```

---

# Storage Multi-Tenant

## AWS S3

Estructura:

```text
companies/

company-1/
company-2/
company-3/
company-n/
```

---

## Ejemplo

```text
companies/15/tickets/
companies/15/invoices/
companies/15/contracts/
companies/15/uploads/
```

---

# Cache Multi-Tenant

## Redis

```text
tenant:{company_id}:settings
tenant:{company_id}:permissions
tenant:{company_id}:dashboard
tenant:{company_id}:analytics
```

---

# Queue Multi-Tenant

Todos los Jobs deberán almacenar:

```php
company_id
```

---

# Notifications Multi-Tenant

Todas las notificaciones deberán incluir:

```text
company_id
```

---

# Reports Multi-Tenant

Todas las consultas deberán incluir:

```sql
WHERE company_id = ?
```

---

# Analytics Multi-Tenant

KPIs independientes por empresa.

```text
Tickets
Facturación
Inventario
Clientes
CRM
Reparaciones
```

---

# AI Multi-Tenant

La IA nunca podrá compartir contexto entre empresas.

Cada conversación deberá respetar:

```text
company_id
```

---

# Knowledge Base Multi-Tenant

Separación total por empresa.

```text
Company A

Tickets
Documentos
Procedimientos

Company B

Tickets
Documentos
Procedimientos
```

---

# Seguridad

Capas de protección:

```text
TenantMiddleware
CompanyScope
Policies
Permissions
Ownership Checks
```

---

# Auditoría

Registrar:

```text
Tenant Access
Tenant Initialized
Tenant Switch
Cross Access Attempt
Unauthorized Access
```

---

# Eventos

```text
TenantResolved
TenantInitialized
TenantContextLoaded
TenantAccessDenied
```

---

# Jobs

```text
InitializeTenantJob
SyncTenantSettingsJob
GenerateTenantAnalyticsJob
```

---

# Testing

## Unit Tests

```text
TenantResolverTest
TenantManagerTest
CompanyScopeTest
```

---

## Feature Tests

```text
CrossTenantAccessTest
TenantDashboardTest
TenantIsolationTest
```

---

# Escalabilidad Inicial

```text
100 Empresas
10.000 Usuarios
```

---

# Escalabilidad Enterprise

```text
10.000 Empresas
1.000.000 Usuarios
```

---

# Monitoreo

Registrar:

```text
Tenant Usage
Storage Usage
Active Users
API Requests
AI Consumption
Queue Usage
```

---

# Reglas de Negocio

## Regla 1

Todo dato empresarial debe pertenecer a una empresa.

---

## Regla 2

No se permite acceso cruzado entre empresas.

---

## Regla 3

Toda consulta debe respetar company_id.

---

## Regla 4

Toda acción debe quedar auditada.

---

## Regla 5

Los archivos deben almacenarse por tenant.

---

## Regla 6

La IA debe mantener aislamiento completo entre empresas.

---

# Resultado Esperado

IAtechs Pro operará como una plataforma SaaS Enterprise Multi-Tenant segura, escalable y preparada para miles de empresas, garantizando aislamiento total de datos, almacenamiento independiente, analíticas separadas y cumplimiento de estándares empresariales.
