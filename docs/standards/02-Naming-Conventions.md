# IAtechs Pro

# Development Standards

## 02-Naming-Conventions

---

# Objetivo

Definir las convenciones oficiales de nombres utilizadas en IAtechs Pro para garantizar consistencia, mantenibilidad y escalabilidad en toda la plataforma.

---

# Alcance

Aplica a:

```text
Backend

Frontend

Database

APIs

Services

Repositories

Events

Jobs

Tests

Infrastructure
```

---

# Principios

Todos los nombres deben ser:

```text
Descriptivos

Consistentes

Predecibles

Escalables
```

---

# Estructura Principal

```text
app/

Domains/

Tenant/

Providers/

Traits/

Policies/

Jobs/

Events/
```

---

# Dominios

Formato:

```text
PascalCase
```

---

## Correcto

```text
Companies

Users

Tickets

Accounting

KnowledgeBase

BusinessIntelligence
```

---

## Incorrecto

```text
companies

knowledge_base

business-intelligence
```

---

# Modelos

Formato:

```text
PascalCase Singular
```

---

## Correcto

```php
Company

User

Ticket

Invoice

Payment
```

---

## Incorrecto

```php
Companies

Users

Tickets
```

---

# Controllers

Formato:

```text
EntityController
```

---

## Correcto

```php
CompanyController

TicketController

InvoiceController
```

---

# Services

Formato:

```text
EntityService
```

---

## Correcto

```php
CompanyService

TicketService

AccountingService
```

---

# Repositories

Formato:

```text
EntityRepository
```

---

## Correcto

```php
CompanyRepository

TicketRepository

UserRepository
```

---

# Requests

Formato:

```text
ActionEntityRequest
```

---

## Correcto

```php
StoreCompanyRequest

UpdateCompanyRequest

StoreTicketRequest

CloseTicketRequest
```

---

# Resources

Formato:

```text
EntityResource
```

---

## Correcto

```php
CompanyResource

UserResource

InvoiceResource
```

---

# Policies

Formato:

```text
EntityPolicy
```

---

## Correcto

```php
CompanyPolicy

TicketPolicy

InvoicePolicy
```

---

# Traits

Formato:

```text
VerbOrConcept
```

---

## Correcto

```php
BelongsToCompany

HasUuid

HasAuditTrail
```

---

# Interfaces

Formato:

```text
SomethingInterface
```

---

## Correcto

```php
TenantResolverInterface

AIProviderInterface

StorageInterface
```

---

# Events

Formato:

```text
PastTense
```

---

## Correcto

```php
TicketCreated

InvoicePaid

CustomerRegistered

TenantResolved
```

---

# Listeners

Formato:

```text
HandleSomething
```

---

## Correcto

```php
HandleTicketCreated

HandleInvoicePaid
```

---

# Jobs

Formato:

```text
VerbNounJob
```

---

## Correcto

```php
GenerateInvoiceJob

SyncInventoryJob

ProcessAIRequestJob
```

---

# Notifications

Formato:

```text
SomethingNotification
```

---

## Correcto

```php
InvoicePaidNotification

TicketAssignedNotification

PasswordResetNotification
```

---

# Commands

Formato:

```text
VerbSomethingCommand
```

---

## Correcto

```php
SyncCompaniesCommand

GenerateReportsCommand
```

---

# Tests

Formato:

```text
EntityTest
```

---

## Unit

```php
CompanyRepositoryTest

AccountingServiceTest
```

---

## Feature

```php
CreateCompanyTest

CreateTicketTest
```

---

# Variables

Formato:

```text
camelCase
```

---

## Correcto

```php
$companyId

$ticketStatus

$totalAmount
```

---

## Incorrecto

```php
$CompanyID

$ticket_status
```

---

# Métodos

Formato:

```text
camelCase
```

---

## Correcto

```php
createCompany()

closeTicket()

generateInvoice()
```

---

# Constantes

Formato:

```text
UPPER_CASE
```

---

## Correcto

```php
MAX_RETRIES

DEFAULT_TIMEOUT

CACHE_TTL
```

---

# Tablas

Formato:

```text
snake_case plural
```

---

## Correcto

```sql
companies

users

tickets

audit_logs

knowledge_articles
```

---

## Incorrecto

```sql
Company

Ticket

User
```

---

# Columnas

Formato:

```text
snake_case
```

---

## Correcto

```sql
company_id

created_at

updated_at

total_amount
```

---

# Foreign Keys

Formato:

```text
entity_id
```

---

## Correcto

```sql
company_id

ticket_id

invoice_id

customer_id
```

---

# Pivot Tables

Formato:

```text
alphabetical_order
```

---

## Correcto

```sql
role_user

permission_role

project_user
```

---

# Migraciones

Formato:

```text
create_entities_table

add_column_to_entities_table
```

---

## Correcto

```text
create_companies_table

create_tickets_table

add_status_to_tickets_table
```

---

# Seeders

Formato:

```text
EntitySeeder
```

---

## Correcto

```php
CompanySeeder

UserSeeder

RoleSeeder

PermissionSeeder
```

---

# APIs

Formato:

```text
/api/v1/resource
```

---

## Correcto

```http
/api/v1/companies

/api/v1/tickets

/api/v1/invoices
```

---

# Permisos

Formato:

```text
module.action
```

---

## Correcto

```text
companies.view

companies.create

companies.update

companies.delete

tickets.assign

tickets.close
```

---

# Roles

Formato:

```text
snake_case
```

---

## Correcto

```text
super_admin

owner

manager

technician

customer
```

---

# Cache Keys

Formato:

```text
tenant:{company_id}:resource
```

---

## Correcto

```text
tenant:1:settings

tenant:1:permissions

tenant:1:dashboard
```

---

# Storage

Formato:

```text
companies/{company_id}/resource
```

---

## Correcto

```text
companies/15/invoices

companies/15/tickets

companies/15/contracts
```

---

# Variables ENV

Formato:

```text
UPPER_CASE
```

---

## Correcto

```env
APP_NAME

DB_HOST

AWS_BUCKET

OPENAI_API_KEY
```

---

# Rutas Web

Formato:

```text
kebab-case
```

---

## Correcto

```text
/companies

/customer-devices

/service-contracts
```

---

# Archivos Markdown

Formato:

```text
NN-Name.md
```

---

## Correcto

```text
01-Folder-Structure.md

03-MultiTenant-Architecture.md

40-BusinessIntelligence.md
```

---

# Reglas Prohibidas

Nunca usar:

```text
Abreviaciones ambiguas

Nombres genéricos

Variables de una letra

Clases llamadas ManagerHelperUtil
```

---

## Incorrecto

```php
$data

$obj

$tmp

$var
```

---

# Resultado Esperado

Toda la plataforma IAtechs Pro deberá seguir estas convenciones para garantizar uniformidad entre módulos, facilitar mantenimiento, acelerar el desarrollo y mantener estándares Enterprise en todos los componentes del sistema.
