# IAtechs Pro

# Development

## 04-Tenant-Implementation

---

# Objetivo

Definir la implementación oficial de la arquitectura Multi-Tenant de IAtechs Pro utilizando Laravel 12, PostgreSQL y aislamiento por company_id.

---

# Alcance

Aplica a:

```text
Todos los módulos empresariales
```

---

# Arquitectura Seleccionada

```text
Shared Database

Shared Schema

Tenant Isolation by company_id
```

---

# Principio Fundamental

```text
Ningún tenant podrá acceder a datos de otro tenant.
```

---

# Tenant Principal

## Company

Tabla raíz:

```text
companies
```

---

# Relación Principal

```text
Company

  ↓

Users
Customers
Devices
Tickets
Invoices
Inventory
CRM
KnowledgeBase
Projects
Assets
```

---

# Campo Obligatorio

Todos los módulos empresariales deben incluir:

```sql
company_id
```

---

# Excepciones

No utilizan tenant:

```text
companies

plans

subscriptions
```

---

# Estructura Tenant

Ubicación:

```text
app/Tenant
```

---

# Organización

```text
app/Tenant/

├── Contracts
├── Middleware
├── Scopes
├── Services
├── Traits
├── TenantManager.php
├── TenantResolver.php
├── TenantServiceProvider.php
└── CompanyScope.php
```

---

# TenantManager

Ubicación:

```text
app/Tenant/TenantManager.php
```

---

# Responsabilidad

Gestionar tenant actual.

---

# Métodos

```php
current()

set()

forget()

id()

company()
```

---

# Ejemplo

```php
app(TenantManager::class)
    ->current();
```

---

# TenantResolver

Ubicación:

```text
app/Tenant/TenantResolver.php
```

---

# Responsabilidad

Resolver tenant autenticado.

---

# Flujo

```text
Request

↓

User

↓

Company

↓

TenantManager
```

---

# Métodos

```php
resolve()

resolveFromUser()

resolveFromRequest()
```

---

# TenantMiddleware

Ubicación:

```text
app/Tenant/Middleware/TenantMiddleware.php
```

---

# Responsabilidad

Inicializar tenant.

---

# Flujo

```text
Request
   ↓
Auth
   ↓
TenantMiddleware
   ↓
TenantManager
```

---

# Registro

```php
bootstrap/providers.php
```

---

# Provider

```php
App\Tenant\TenantServiceProvider::class
```

---

# CompanyScope

Ubicación:

```text
app/Tenant/Scopes/CompanyScope.php
```

---

# Responsabilidad

Filtrar consultas automáticamente.

---

# Ejemplo

```php
where('company_id', $tenantId)
```

---

# Global Scope

Ejemplo:

```php
protected static function booted()
{
    static::addGlobalScope(
        new CompanyScope()
    );
}
```

---

# Trait Oficial

## BelongsToCompany

Ubicación:

```text
app/Tenant/Traits/BelongsToCompany.php
```

---

# Responsabilidad

Agregar tenant automático.

---

# Uso

```php
use BelongsToCompany;
```

---

# Ejemplo

```php
class Customer extends Model
{
    use BelongsToCompany;
}
```

---

# Asignación Automática

Al crear registros:

```php
company_id
```

debe asignarse automáticamente.

---

# Ejemplo

```php
$model->company_id =
    tenant()->id();
```

---

# Helper Tenant

Crear helper global:

```php
tenant()
```

---

# Ejemplo

```php
tenant()->id();

tenant()->company();
```

---

# Tenant Context

Información disponible:

```php
tenant()->id();

tenant()->company();

tenant()->name();
```

---

# Modelos Empresariales

Deben usar:

```php
use BelongsToCompany;
```

---

# Ejemplos

```text
Customer

Device

Ticket

Invoice

Payment

Inventory

Supplier

Project

Asset
```

---

# Relaciones

Ejemplo:

```php
public function company()
{
    return $this->belongsTo(
        Company::class
    );
}
```

---

# Validación de Tenant

Antes de acceder:

```php
company_id
```

debe coincidir.

---

# Ejemplo

```php
abort_if(
    $model->company_id !== tenant()->id(),
    403
);
```

---

# Repositories

Todos los repositories deben respetar tenant.

---

# Correcto

```php
Customer::query()
```

porque el scope ya filtra.

---

# Incorrecto

```php
Customer::withoutGlobalScopes()
```

---

# Solo Permitido

```php
Super Admin
```

---

# Policies

Validar tenant ownership.

---

# Ejemplo

```php
return
$user->company_id ===
$model->company_id;
```

---

# API

Toda API debe operar bajo tenant actual.

---

# Ejemplo

```text
/api/v1/customers
```

solo devuelve:

```text
Customers del tenant activo
```

---

# Queue Jobs

Todos los jobs deben transportar:

```php
company_id
```

---

# Ejemplo

```php
class GenerateInvoiceJob
{
    public int $companyId;
}
```

---

# Inicialización Job

```php
tenant()->id()
```

---

# Restauración Job

```php
TenantManager::set()
```

---

# Eventos

Todos los eventos empresariales deben incluir:

```php
company_id
```

---

# Ejemplo

```php
CustomerCreated
```

---

# Payload

```php
public int $companyId;
```

---

# Notifications

Deben incluir:

```text
company_id
```

---

# Storage

Estructura oficial:

```text
companies/

├── 1/
├── 2/
├── 3/
└── n/
```

---

# Ejemplo

```text
companies/15/tickets

companies/15/contracts

companies/15/invoices
```

---

# Redis

Formato:

```text
tenant:{id}:settings

tenant:{id}:dashboard

tenant:{id}:analytics
```

---

# Cache

Nunca compartir cache.

---

# AI

Regla crítica:

```text
La IA jamás comparte contexto entre tenants.
```

---

# Knowledge Base

Separación:

```text
Tenant A

↓

Tickets
Docs
Procedures
```

---

```text
Tenant B

↓

Tickets
Docs
Procedures
```

---

# Auditoría

Registrar:

```text
TenantResolved

TenantInitialized

TenantAccessDenied

CrossTenantAttempt
```

---

# Testing

## Unit

```text
TenantManagerTest

TenantResolverTest

CompanyScopeTest
```

---

# Feature

```text
TenantIsolationTest

CrossTenantAccessTest

TenantDashboardTest
```

---

# Regla de Oro

```text
Todo dato empresarial debe pertenecer a un company_id.
```

---

# Resultado Esperado

IAtechs Pro deberá garantizar aislamiento total entre empresas mediante TenantManager, TenantResolver, CompanyScope y BelongsToCompany, asegurando que cada tenant opere sobre sus propios datos sin posibilidad de acceso cruzado dentro de la plataforma SaaS.
