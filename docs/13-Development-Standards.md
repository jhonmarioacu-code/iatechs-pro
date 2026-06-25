# Development Standards

# IAtechs Pro

## Estándares Oficiales de Desarrollo

---

# Objetivo

Definir las reglas, convenciones y estándares obligatorios para el desarrollo de IAtechs Pro.

Todos los módulos, servicios, APIs y componentes deberán cumplir estas normas.

---

# Filosofía de Desarrollo

## Principios

* Clean Code
* SOLID
* DRY (Don't Repeat Yourself)
* KISS (Keep It Simple)
* Separation of Concerns
* Domain Driven Design (DDD Light)

---

# Stack Tecnológico Oficial

## Backend

* Laravel 12
* PHP 8.3+
* PostgreSQL 16+
* Redis

---

## Frontend

* Blade
* Tailwind CSS
* Alpine.js
* Livewire 3

---

## Infraestructura

* AWS EC2
* Ubuntu Server 24.04 LTS
* Nginx
* Supervisor

---

# Arquitectura Oficial

## Patrón Enterprise

```text
Controller
    ↓
Request
    ↓
Service
    ↓
Repository
    ↓
Model
    ↓
Database
```

---

# Estructura Oficial del Proyecto

```text
app/
├── Console/
├── DTOs/
├── Events/
├── Exceptions/
├── Helpers/
├── Http/
│   ├── Controllers/
│   ├── Middleware/
│   ├── Requests/
│   └── Resources/
├── Jobs/
├── Listeners/
├── Mail/
├── Models/
├── Notifications/
├── Observers/
├── Policies/
├── Providers/
├── Repositories/
├── Services/
└── Traits/
```

---

# Controllers

## Responsabilidades

Los Controllers solamente podrán:

* Recibir solicitudes.
* Validar permisos.
* Llamar Services.
* Retornar respuestas.

---

## Prohibido

* Consultas SQL directas.
* Reglas de negocio.
* Procesos complejos.
* Lógica financiera.
* Manipulación masiva de datos.

---

# Services

## Responsabilidad

Toda lógica de negocio debe vivir aquí.

Ejemplos:

```text
CompanyService
UserService
TicketService
InventoryService
InvoiceService
AiService
```

---

# Repositories

## Responsabilidad

Acceso a datos.

Ejemplos:

```text
CompanyRepository
UserRepository
TicketRepository
ProductRepository
InvoiceRepository
```

---

# Models

Cada modelo representa una entidad de negocio.

Ejemplos:

```text
Company
User
Customer
Device
Ticket
Invoice
Subscription
```

---

## Obligatorio

Todos los modelos deberán definir:

```php
$fillable
$casts
relationships()
scopes()
```

---

# Requests

Toda validación deberá implementarse mediante Form Requests.

Ejemplos:

```text
StoreCompanyRequest
StoreCustomerRequest
CreateTicketRequest
UpdateInvoiceRequest
```

---

# Resources

Toda respuesta API deberá utilizar:

```php
JsonResource
```

Ejemplos:

```text
CompanyResource
CustomerResource
TicketResource
InvoiceResource
```

---

# DTOs

Objetivo:

Transferencia segura de datos entre capas.

Ejemplos:

```text
CreateCompanyDTO
CreateTicketDTO
CreateInvoiceDTO
```

---

# Policies

Cada módulo deberá poseer su Policy.

Ejemplos:

```text
CompanyPolicy
TicketPolicy
CustomerPolicy
InvoicePolicy
```

---

# Middleware Personalizados

```text
CompanyMiddleware
SubscriptionMiddleware
AuditMiddleware
ActiveCompanyMiddleware
```

---

# Eventos

Ejemplos:

```text
CompanyCreated
TicketCreated
InvoicePaid
RepairCompleted
SubscriptionExpired
```

---

# Listeners

Ejemplos:

```text
SendNotificationListener
CreateAuditListener
UpdateStatisticsListener
```

---

# Jobs

Procesos pesados deben ejecutarse mediante colas.

Ejemplos:

```text
SendEmailJob
GeneratePdfJob
AiAnalysisJob
BackupDatabaseJob
```

---

# Convenciones de Base de Datos

## Tablas

Formato:

```text
snake_case plural
```

Ejemplos:

```text
companies
users
tickets
subscriptions
company_settings
```

---

## Columnas

Formato:

```text
snake_case
```

Ejemplos:

```text
company_id
created_at
updated_at
deleted_at
```

---

# Multiempresa

## Regla Obligatoria

Toda entidad empresarial deberá contener:

```php
company_id
```

---

## Scope Global

Implementar:

```php
CompanyScope
```

para filtrar automáticamente la información de cada empresa.

---

# Soft Deletes

Aplicar obligatoriamente a:

```text
Customers
Products
Tickets
Invoices
Companies
```

---

# APIs

## Versionado

```text
/api/v1
```

---

## Respuesta Exitosa

```json
{
  "success": true,
  "message": "Operación exitosa",
  "data": {}
}
```

---

## Respuesta Error

```json
{
  "success": false,
  "message": "Error",
  "errors": {}
}
```

---

# Seguridad

## Obligatorio

* Policies
* Permissions
* Middleware
* Validaciones
* Rate Limiting
* Auditoría

---

## Prohibido

* Permisos hardcodeados.
* Consultas sin company_id.
* Acceso directo a datos de otra empresa.
* Credenciales en código fuente.

---

# Logging

Nunca utilizar en producción:

```php
dd();
dump();
var_dump();
```

---

Utilizar:

```php
Log::info();
Log::warning();
Log::error();
Log::critical();
```

---

# Testing

## Unit Tests

```text
tests/Unit
```

---

## Feature Tests

```text
tests/Feature
```

---

## Cobertura Mínima

```text
80%
```

---

# Git Flow Oficial

## Rama Principal

```text
main
```

---

## Desarrollo

```text
develop
```

---

## Features

```text
feature/companies
feature/tickets
feature/inventory
feature/finance
```

---

## Hotfix

```text
hotfix/login
hotfix/subscriptions
```

---

# Convención de Commits

```text
feat: crear módulo companies
fix: corregir validación tickets
refactor: optimizar CompanyService
docs: actualizar arquitectura
test: agregar pruebas módulo CRM
```

---

# Frontend

## Componentes

Ubicación:

```text
resources/views/components
```

---

## Tecnologías

* Blade
* Livewire 3
* Alpine.js
* Tailwind CSS

---

## Regla

No utilizar CSS inline.

---

# Calidad de Código

Herramientas obligatorias:

```text
Laravel Pint
PHPStan
PHP Insights
```

---

# Documentación de Módulos

Cada módulo deberá incluir:

```text
Objetivo
Flujo
Roles
Permisos
Tablas
Endpoints
Casos de Uso
```

---

# Auditoría

Toda acción crítica deberá registrar:

```text
Usuario
Empresa
IP
Acción
Módulo
Fecha
Hora
```

---

# Integraciones

Todas las integraciones externas deberán implementarse mediante Services dedicados.

Ejemplos:

```text
OpenAIService
GroqService
WhatsAppService
WompiService
StripeService
S3Service
```

---

# Regla Principal

Ninguna implementación podrá aprobarse si incumple:

```text
Arquitectura Enterprise
Seguridad
Escalabilidad
Multiempresa
Mantenibilidad
Trazabilidad
```

---

# Resultado Esperado

IAtechs Pro deberá desarrollarse bajo estándares empresariales modernos, garantizando calidad de código, escalabilidad SaaS, seguridad multiempresa, integración con inteligencia artificial y facilidad de mantenimiento a largo plazo.
