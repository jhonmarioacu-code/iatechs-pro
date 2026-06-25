# IAtechs Pro

# Development Standards

## 03-DDD-Standards

---

# Objetivo

Definir el estándar oficial de Domain-Driven Design (DDD) utilizado en IAtechs Pro para garantizar escalabilidad, mantenibilidad y separación adecuada de responsabilidades en toda la plataforma.

---

# Definición

IAtechs Pro utiliza una arquitectura:

```text
Domain Driven Design (DDD)

Modular Monolith

Multi-Tenant SaaS

Laravel 12
```

---

# Principio Fundamental

La aplicación se organiza por:

```text
Dominios de Negocio
```

y no por:

```text
Controllers

Models

Services
```

---

# Correcto

```text
Domains

├── Tickets
├── Customers
├── Inventory
├── Accounting
├── AI
```

---

# Incorrecto

```text
Controllers

Models

Repositories

Services
```

---

# Estructura Oficial

```text
app/

Domains/

├── Companies
├── Users
├── Tickets
├── Inventory
├── Accounting
├── CRM
├── KnowledgeBase
└── ...
```

---

# Estructura de Dominio

Ejemplo:

```text
app/Domains/Tickets
```

---

# Contenido

```text
Controllers

Models

Services

Repositories

Requests

Resources

Policies

Events

Jobs

DTOs

Enums
```

---

# Ejemplo Completo

```text
Tickets/

├── Controllers
├── Models
├── Services
├── Repositories
├── Requests
├── Resources
├── Policies
├── Events
├── Jobs
├── DTOs
└── Enums
```

---

# Dominio

Un dominio representa:

```text
Una capacidad de negocio
```

---

# Ejemplos

```text
Customers

Tickets

Repairs

Inventory

Accounting

CRM

HR
```

---

# Regla

Todo código debe pertenecer a un dominio.

---

# Prohibido

```text
app/Services

app/Repositories

app/Models
```

globales.

---

# Controladores

Responsabilidad:

```text
HTTP Layer
```

---

# Deben

```text
Recibir Request

Autorizar

Llamar Service

Retornar Response
```

---

# Nunca

```text
Lógica de negocio

Consultas complejas

Procesos largos
```

---

# Services

Responsabilidad:

```text
Business Logic
```

---

# Ejemplos

```php
CreateTicket

CloseTicket

GenerateInvoice

AssignTechnician
```

---

# Repositories

Responsabilidad:

```text
Persistencia
```

---

# Ejemplos

```php
find()

create()

update()

delete()

paginate()
```

---

# Regla

Los Services nunca acceden directamente a Eloquent.

---

# Siempre

```text
Service
 ↓
Repository
 ↓
Model
```

---

# Models

Responsabilidad:

```text
Representar entidades
```

---

# Permitido

```text
Relationships

Scopes

Accessors

Mutators
```

---

# Prohibido

```text
Business Logic

External APIs

Complex Workflows
```

---

# Requests

Responsabilidad:

```text
Validation
```

---

# Ejemplo

```php
StoreTicketRequest

UpdateInvoiceRequest
```

---

# Policies

Responsabilidad:

```text
Authorization
```

---

# Ejemplo

```php
TicketPolicy

InvoicePolicy
```

---

# Resources

Responsabilidad:

```text
Transformar Responses
```

---

# Ejemplo

```php
TicketResource

InvoiceResource
```

---

# DTO

Data Transfer Object.

---

# Ubicación

```text
Domain/DTOs
```

---

# Ejemplo

```php
CreateTicketDTO

CreateInvoiceDTO
```

---

# Uso

```text
Request
 ↓
DTO
 ↓
Service
```

---

# Enums

Ubicación:

```text
Domain/Enums
```

---

# Ejemplo

```php
TicketStatus

InvoiceStatus

PaymentStatus
```

---

# Eventos

Ubicación:

```text
Domain/Events
```

---

# Ejemplo

```php
TicketCreated

InvoicePaid

CustomerRegistered
```

---

# Regla

Eventos representan:

```text
Algo que ya ocurrió
```

---

# Jobs

Ubicación:

```text
Domain/Jobs
```

---

# Ejemplo

```php
GenerateInvoicePDFJob

SyncInventoryJob

AnalyzeTicketJob
```

---

# Tenant Isolation

Todos los dominios empresariales deben:

```text
Implementar company_id
```

---

# Excepciones

```text
Plans

Subscriptions

Super Admin
```

---

# Comunicación entre Dominios

Permitida mediante:

```text
Services

Events

DTOs
```

---

# Prohibido

```text
Acceso directo a tablas externas
```

---

# Ejemplo Correcto

```text
Tickets
 ↓
CustomerService
 ↓
Customers
```

---

# Ejemplo Incorrecto

```text
Tickets
 ↓
Customers Table
```

---

# Dependencias

Regla:

```text
Dominio A no controla Dominio B
```

---

# Correcto

```text
Accounting
 ↓
InvoiceService
```

---

# Incorrecto

```text
Accounting modifica internamente Inventory
```

---

# Multi Tenant

Todos los dominios deben:

```text
BelongsToCompany
```

---

# Trait Oficial

```php
BelongsToCompany
```

---

# Domain Events

Ejemplos:

```text
CustomerCreated

TicketClosed

InvoicePaid

PaymentRegistered
```

---

# Domain Services

Ejemplos:

```text
TicketService

InvoiceService

CustomerService

AIService
```

---

# Domain Repositories

Ejemplos:

```text
TicketRepository

InvoiceRepository

CustomerRepository
```

---

# Flujo Oficial

```text
Request
 ↓
Validation
 ↓
DTO
 ↓
Service
 ↓
Repository
 ↓
Model
 ↓
Resource
 ↓
Response
```

---

# Testing DDD

Cada dominio debe tener:

```text
Unit Tests

Feature Tests
```

---

# Ejemplo

```text
tests/

├── Unit/Tickets
└── Feature/Tickets
```

---

# Reglas Prohibidas

Nunca:

```text
Controller → Model
```

directamente.

---

Nunca:

```text
Controller → DB
```

---

Nunca:

```text
View → Query
```

---

Nunca:

```text
Service → Auth::user()
```

---

Nunca:

```text
Service → Request
```

---

# Objetivo Final

Cada dominio de IAtechs Pro debe ser independiente, reutilizable, mantenible y escalable, siguiendo los principios de Domain-Driven Design para soportar una plataforma SaaS Enterprise Multi-Tenant preparada para miles de empresas y millones de registros.
