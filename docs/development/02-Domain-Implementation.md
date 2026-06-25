# IAtechs Pro

# Development

## 02-Domain-Implementation

---

# Objetivo

Definir el estándar oficial para implementar dominios dentro de IAtechs Pro utilizando Domain Driven Design (DDD), Laravel 12 y arquitectura Multi-Tenant.

---

# Alcance

Aplica a:

```text
Todos los módulos del sistema
```

---

# Dominios Oficiales

```text
01-Companies
02-Subscriptions
03-Plans
04-Users
05-Roles-Permissions
06-Branches
07-Customers
08-Devices
09-Tickets
10-Diagnostics
11-Quotes
12-Repairs
13-Inventory
14-Suppliers
15-Purchases
16-PurchaseOrders
17-GoodsReceipts
18-Invoices
19-Payments
20-Warranties
21-ServiceContracts
22-WorkOrders
23-TechnicianSchedules
24-Notifications
25-Reports
26-Analytics
27-AuditLogs
28-FileManager
29-AI-Assistant
30-SystemSettings
31-CRM
32-KnowledgeBase
33-Accounting
34-HumanResources
35-Projects
36-Assets
37-Procurement
38-DocumentManagement
39-Compliance
40-BusinessIntelligence
```

---

# Estructura Obligatoria

Cada dominio deberá vivir dentro de:

```text
app/Domains
```

---

# Ejemplo

```text
app/Domains/Customers
```

---

# Estructura Estándar

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
└── Tests
```

---

# Model

Responsabilidad:

```text
Representar la entidad.
```

---

# Ejemplo

```text
Customer.php
```

---

# Reglas

```text
Fillable

Casts

Relations

Scopes
```

---

# Repository

Responsabilidad:

```text
Acceso a datos.
```

---

# Ejemplo

```text
CustomerRepository.php
```

---

# Métodos Recomendados

```php
find()

findByUuid()

create()

update()

delete()

paginate()
```

---

# Service

Responsabilidad:

```text
Casos de uso del negocio.
```

---

# Ejemplo

```text
CustomerService.php
```

---

# Métodos Recomendados

```php
createCustomer()

updateCustomer()

activateCustomer()

deactivateCustomer()
```

---

# Request

Responsabilidad:

```text
Validación.
```

---

# Ejemplo

```text
StoreCustomerRequest

UpdateCustomerRequest
```

---

# Policy

Responsabilidad:

```text
Autorización.
```

---

# Ejemplo

```text
CustomerPolicy
```

---

# DTO

Responsabilidad:

```text
Transferencia de datos.
```

---

# Ejemplo

```text
CustomerData
```

---

# Actions

Responsabilidad:

```text
Procesos específicos.
```

---

# Ejemplo

```text
ImportCustomerAction

ExportCustomerAction
```

---

# Events

Responsabilidad:

```text
Eventos del dominio.
```

---

# Ejemplo

```text
CustomerCreated

CustomerUpdated
```

---

# Listeners

Responsabilidad:

```text
Responder eventos.
```

---

# Ejemplo

```text
SendWelcomeEmail
```

---

# Jobs

Responsabilidad:

```text
Procesamiento en segundo plano.
```

---

# Ejemplo

```text
GenerateCustomerReportJob
```

---

# Exceptions

Responsabilidad:

```text
Errores del dominio.
```

---

# Ejemplo

```text
CustomerNotFoundException
```

---

# Enums

Responsabilidad:

```text
Valores controlados.
```

---

# Ejemplo

```php
CustomerStatusEnum
```

---

# Flujo Oficial

```text
Request
   ↓
Controller
   ↓
Service
   ↓
Repository
   ↓
Model
```

---

# Nunca

```text
Controller → Model
```

directamente.

---

# Controllers

Ubicación:

```text
app/Http/Controllers
```

---

# Regla

Los controladores deben ser mínimos.

---

# Ejemplo

```php
public function store(
    StoreCustomerRequest $request
)
{
    return $this->service
        ->createCustomer(
            $request->validated()
        );
}
```

---

# Multi-Tenant

Todos los dominios empresariales deben contener:

```text
company_id
```

---

# Trait Obligatorio

```php
use BelongsToCompany;
```

---

# Excepciones

No utilizan tenant:

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

# Identificador Público

```php
uuid
```

---

# Identificador Interno

```php
id
```

---

# Auditoría

Registrar:

```text
Create

Update

Delete

Restore

Critical Actions
```

---

# Eventos Estándar

Cada módulo debe tener:

```text
Created

Updated

Deleted
```

---

# Testing

Todos los dominios deben tener:

```text
Unit Tests

Feature Tests
```

---

# Ejemplo

```text
CustomerServiceTest

CustomerRepositoryTest

CreateCustomerTest

UpdateCustomerTest
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

# Estándar para Nuevos Módulos

Al crear un módulo nuevo se debe construir:

```text
Model

Migration

Repository

Service

Requests

Policy

Events

Tests
```

---

# Convención de Nombres

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

Request

```text
StoreCustomerRequest
```

Policy

```text
CustomerPolicy
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

# Shared Layer

Código reutilizable:

```text
app/Shared
```

---

# Infrastructure Layer

Integraciones externas:

```text
app/Infrastructure
```

---

# Tenant Layer

```text
app/Tenant
```

---

# AI Layer

```text
app/AI
```

---

# Resultado Esperado

Todos los módulos de IAtechs Pro deberán implementarse utilizando una estructura DDD uniforme, Multi-Tenant, escalable y desacoplada, permitiendo crecimiento empresarial sin degradar la mantenibilidad ni la calidad del código.
