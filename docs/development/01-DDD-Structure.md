# IAtechs Pro

# Development

## 01-DDD-Structure

---

# Objetivo

Definir la estructura oficial de desarrollo basada en Domain Driven Design (DDD) utilizada por IAtechs Pro.

---

# Alcance

Aplica a:

```text
Todos los módulos

Backend

API

Jobs

Services

Repositories

Policies

AI Services
```

---

# Principio Fundamental

IAtechs Pro utiliza:

```text
DDD + Laravel 12 + Multi-Tenant SaaS
```

---

# Objetivos

```text
Escalabilidad

Mantenibilidad

Separación de responsabilidades

Bajo acoplamiento

Alta cohesión
```

---

# Arquitectura General

```text
app/

├── Domains/
├── Shared/
├── Infrastructure/
├── Tenant/
├── AI/
└── Console/
```

---

# Domains

Contiene toda la lógica de negocio.

```text
app/Domains
```

---

# Ejemplo

```text
app/Domains/

├── Companies
├── Users
├── Customers
├── Tickets
├── Diagnostics
├── Repairs
├── Inventory
├── Invoices
├── CRM
├── KnowledgeBase
└── AIAssistant
```

---

# Estructura de un Dominio

Ejemplo:

```text
app/Domains/Customers
```

---

# Organización

```text
Customers/

├── Models/
├── Repositories/
├── Services/
├── Requests/
├── Policies/
├── DTOs/
├── Actions/
├── Events/
├── Listeners/
├── Jobs/
└── Exceptions/
```

---

# Models

Responsabilidad:

```text
Representar entidades del negocio.
```

---

# Ejemplo

```text
Customers/Models/Customer.php
```

---

# Repositories

Responsabilidad:

```text
Acceso a datos.
```

---

# Prohibido

```text
Lógica de negocio compleja.
```

---

# Ejemplo

```text
CustomerRepository
```

---

# Services

Responsabilidad:

```text
Reglas de negocio.

Casos de uso.

Procesos.
```

---

# Ejemplo

```text
CustomerService
```

---

# Requests

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

# Policies

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

# DTOs

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
Operaciones específicas.
```

---

# Ejemplo

```text
CreateCustomerAction

ImportCustomerAction
```

---

# Events

Responsabilidad:

```text
Eventos de dominio.
```

---

# Ejemplo

```text
CustomerCreated
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
Procesamiento asíncrono.
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
Errores de dominio.
```

---

# Ejemplo

```text
CustomerNotFoundException
```

---

# Shared Layer

Ubicación:

```text
app/Shared
```

---

# Responsabilidad

Código reutilizable.

---

# Contenido

```text
Traits

Helpers

Enums

Contracts

DTOs Compartidos
```

---

# Infrastructure Layer

Ubicación:

```text
app/Infrastructure
```

---

# Responsabilidad

Integraciones externas.

---

# Ejemplos

```text
AWS

Redis

S3

Email

SMS

Payments
```

---

# Tenant Layer

Ubicación:

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

# AI Layer

Ubicación:

```text
app/AI
```

---

# Componentes

```text
AIManager

Providers

Prompts

Embeddings

KnowledgeBase
```

---

# Controllers

Ubicación

```text
app/Http/Controllers
```

---

# Regla

Los controladores deben ser ligeros.

---

# Ejemplo

```php
public function store(
    StoreCustomerRequest $request
)
{
    return $this->service->create(
        $request->validated()
    );
}
```

---

# Prohibido

```text
Lógica compleja en Controllers.
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

# Multi-Tenant

Todos los dominios empresariales deben incluir:

```text
company_id
```

---

# Excepciones

```text
Companies

Plans

Subscriptions
```

---

# Dependencias

Regla:

```text
Controller

depende de

Service

Service

depende de

Repository

Repository

depende de

Model
```

---

# Nunca

```text
Controller → Model

directamente
```

---

# Naming Convention

Model:

```text
Customer
```

---

# Repository

```text
CustomerRepository
```

---

# Service

```text
CustomerService
```

---

# Policy

```text
CustomerPolicy
```

---

# Request

```text
StoreCustomerRequest

UpdateCustomerRequest
```

---

# Event

```text
CustomerCreated
```

---

# Job

```text
GenerateInvoiceJob
```

---

# Testing

Cada dominio debe tener:

```text
Unit Tests

Feature Tests
```

---

# Ejemplo

```text
tests/

├── Unit/
└── Feature/
```

---

# Unit

```text
CustomerServiceTest

CustomerRepositoryTest
```

---

# Feature

```text
CreateCustomerTest

UpdateCustomerTest
```

---

# Principios de Calidad

```text
SOLID

DRY

KISS

DDD
```

---

# Auditoría

Todos los módulos deben registrar:

```text
Creación

Actualización

Eliminación

Accesos Críticos
```

---

# Resultado Esperado

IAtechs Pro deberá implementar una arquitectura DDD Enterprise basada en Laravel 12, permitiendo desarrollar módulos independientes, escalables, mantenibles y preparados para operar como una plataforma SaaS Multi-Tenant de nivel empresarial.
