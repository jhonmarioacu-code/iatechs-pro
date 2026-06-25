# IAtechs Pro

# Development

## 03-Laravel-Modules

---

# Objetivo

Definir la estructura oficial para registrar, organizar y mantener módulos de negocio dentro de Laravel 12 utilizando arquitectura DDD Enterprise.

---

# Alcance

Aplica a:

```text
Todos los dominios

Todos los módulos

Backend

API

Jobs

AI

Multi-Tenant
```

---

# Principio Fundamental

Cada módulo debe ser independiente.

---

# Objetivo

```text
Bajo Acoplamiento

Alta Cohesión

Escalabilidad

Mantenibilidad
```

---

# Arquitectura Modular

```text
app/

└── Domains/
```

---

# Ejemplo

```text
app/Domains/

├── Companies
├── Users
├── Customers
├── Tickets
├── Repairs
├── Inventory
├── CRM
├── Accounting
├── KnowledgeBase
└── AIAssistant
```

---

# Módulos Oficiales

```text
Companies
Subscriptions
Plans
Users
RolesPermissions
Branches
Customers
Devices
Tickets
Diagnostics
Quotes
Repairs
Inventory
Suppliers
Purchases
PurchaseOrders
GoodsReceipts
Invoices
Payments
Warranties
ServiceContracts
WorkOrders
TechnicianSchedules
Notifications
Reports
Analytics
AuditLogs
FileManager
AIAssistant
SystemSettings
CRM
KnowledgeBase
Accounting
HumanResources
Projects
Assets
Procurement
DocumentManagement
Compliance
BusinessIntelligence
```

---

# Estructura Interna

Ejemplo:

```text
app/Domains/Customers
```

---

# Organización

```text
Customers/

├── Models
├── Repositories
├── Services
├── Requests
├── Policies
├── DTOs
├── Actions
├── Events
├── Listeners
├── Jobs
├── Exceptions
├── Enums
└── Providers
```

---

# Service Provider del Dominio

Cada módulo puede tener:

```text
DomainServiceProvider.php
```

---

# Ejemplo

```text
Customers/

└── Providers/
    └── CustomerServiceProvider.php
```

---

# Responsabilidad

Registrar:

```text
Events

Listeners

Policies

Bindings

Commands
```

---

# Providers Globales

Ubicación:

```text
bootstrap/providers.php
```

---

# Ejemplo

```php
return [

    App\Providers\AppServiceProvider::class,

    App\Providers\AuthServiceProvider::class,

    App\Tenant\TenantServiceProvider::class,

];
```

---

# Controllers

Ubicación Oficial

```text
app/Http/Controllers
```

---

# Organización

```text
Controllers/

├── Companies
├── Users
├── Customers
├── Tickets
├── Inventory
└── API
```

---

# Ejemplo

```text
CustomerController.php
```

---

# API

Ubicación

```text
routes/api.php
```

---

# Organización

```text
api/v1
```

---

# Ejemplo

```php
Route::prefix('v1')->group(function () {

});
```

---

# Web

Ubicación

```text
routes/web.php
```

---

# Responsabilidad

```text
Panel Administrativo

Dashboard

Views
```

---

# Policies

Ubicación

```text
Domains/*/Policies
```

---

# Registro

```php
AuthServiceProvider
```

---

# Ejemplo

```php
Customer::class =>
CustomerPolicy::class
```

---

# Repositories

Cada módulo debe tener:

```text
Repository
```

---

# Regla

No acceder directamente al modelo desde el controlador.

---

# Flujo

```text
Controller
    ↓
Service
    ↓
Repository
    ↓
Model
```

---

# Multi-Tenant

Todos los módulos empresariales deben incluir:

```text
company_id
```

---

# Trait Obligatorio

```php
BelongsToCompany
```

---

# Excepciones

```text
Companies

Plans

Subscriptions
```

---

# UUID

Todos los módulos empresariales deben incluir:

```text
uuid
```

---

# Eventos

Cada módulo debe exponer:

```text
Created

Updated

Deleted
```

---

# Ejemplo

```text
CustomerCreated

CustomerUpdated

CustomerDeleted
```

---

# Jobs

Ubicación

```text
Domains/*/Jobs
```

---

# Ejemplo

```text
GenerateInvoiceJob

SendNotificationJob

SyncAnalyticsJob
```

---

# Shared Layer

Ubicación

```text
app/Shared
```

---

# Responsabilidad

```text
Enums

Traits

Contracts

Helpers

DTOs
```

---

# Infrastructure Layer

Ubicación

```text
app/Infrastructure
```

---

# Responsabilidad

```text
AWS

S3

Redis

Payments

Email

SMS
```

---

# AI Layer

Ubicación

```text
app/AI
```

---

# Componentes

```text
AIManager

Providers

Embeddings

Prompts

KnowledgeBase
```

---

# Tenant Layer

Ubicación

```text
app/Tenant
```

---

# Componentes

```text
TenantManager

TenantResolver

TenantMiddleware

CompanyScope

BelongsToCompany
```

---

# Dependencias Permitidas

```text
Controller
 ↓
Service
 ↓
Repository
 ↓
Model
```

---

# Dependencias Prohibidas

```text
Controller → Model

Model → Controller

Repository → Controller
```

---

# Testing

Cada módulo debe tener:

```text
tests/

Unit

Feature
```

---

# Ejemplo

```text
CustomerServiceTest

CustomerRepositoryTest

CreateCustomerTest
```

---

# Naming Convention

Model

```text
Customer
```

Repository

```text
CustomerRepository
```

Service

```text
CustomerService
```

Policy

```text
CustomerPolicy
```

Request

```text
StoreCustomerRequest
```

Event

```text
CustomerCreated
```

Job

```text
GenerateCustomerReportJob
```

---

# Escalabilidad

Objetivo Inicial

```text
100 Empresas
```

---

# Escalabilidad Enterprise

```text
10.000 Empresas

1.000.000 Usuarios
```

---

# Resultado Esperado

Laravel 12 deberá operar como una plataforma modular Enterprise basada en DDD, donde cada dominio de IAtechs Pro pueda evolucionar de forma independiente, reutilizable, mantenible y completamente compatible con la arquitectura Multi-Tenant SaaS.
