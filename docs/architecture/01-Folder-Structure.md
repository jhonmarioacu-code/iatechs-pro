# IAtechs Pro

# Architecture

## 01-Folder-Structure.md

---

# Objetivo

Definir la estructura oficial de carpetas y archivos para IAtechs Pro bajo una arquitectura Enterprise SaaS Multi-Tenant basada en Laravel 12.

---

# Principios de Arquitectura

* Modular.
* Escalable.
* Multi-Tenant.
* Mantenible.
* Testeable.
* Orientada a Dominio (DDD).
* Compatible con Microservicios futuros.
* Preparada para IA.
* Preparada para AWS.

---

# Estructura General

```text
iatechs-pro/

├── app/
├── bootstrap/
├── config/
├── database/
├── docs/
├── public/
├── resources/
├── routes/
├── storage/
├── tests/
├── vendor/
```

---

# Carpeta App

```text
app/

├── Domains/
├── Http/
├── Services/
├── Repositories/
├── DTOs/
├── Actions/
├── Policies/
├── Events/
├── Listeners/
├── Jobs/
├── Notifications/
├── Observers/
├── Enums/
├── Traits/
├── Helpers/
├── Exceptions/
├── Console/
├── Providers/
└── Models/
```

---

# Arquitectura por Dominio

```text
app/Domains/

├── Companies/
├── Subscriptions/
├── Plans/
├── Users/
├── RolesPermissions/
├── Branches/
├── Customers/
├── Devices/
├── Tickets/
├── Diagnostics/
├── Quotes/
├── Repairs/
├── Inventory/
├── Suppliers/
├── Purchases/
├── PurchaseOrders/
├── GoodsReceipts/
├── Invoices/
├── Payments/
├── Warranties/
├── ServiceContracts/
├── WorkOrders/
├── TechnicianSchedules/
├── Notifications/
├── Reports/
├── Analytics/
├── AuditLogs/
├── FileManager/
├── AIAssistant/
└── SystemSettings/
```

---

# Estructura de Cada Dominio

Ejemplo:

```text
app/Domains/Tickets/

├── Controllers/
├── Services/
├── Repositories/
├── Models/
├── DTOs/
├── Actions/
├── Policies/
├── Events/
├── Listeners/
├── Jobs/
├── Requests/
├── Resources/
├── Observers/
├── Notifications/
└── Tests/
```

---

# HTTP Layer

```text
app/Http/

├── Controllers/
├── Middleware/
├── Requests/
└── Resources/
```

---

# Services

```text
app/Services/

BaseService.php
UploadService.php
TenantService.php
CacheService.php
AIService.php
NotificationService.php
ReportService.php
```

---

# Repositories

```text
app/Repositories/

BaseRepository.php
CompanyRepository.php
TicketRepository.php
CustomerRepository.php
InventoryRepository.php
```

---

# DTOs

```text
app/DTOs/

CompanyDTO.php
TicketDTO.php
CustomerDTO.php
InvoiceDTO.php
```

---

# Actions

```text
app/Actions/

CreateTicketAction.php
GenerateInvoiceAction.php
CreateRepairAction.php
AssignTechnicianAction.php
```

---

# Policies

```text
app/Policies/

TicketPolicy.php
UserPolicy.php
InvoicePolicy.php
RepairPolicy.php
```

---

# Events

```text
app/Events/

TicketCreated.php
InvoiceGenerated.php
RepairCompleted.php
```

---

# Listeners

```text
app/Listeners/

SendTicketNotification.php
GenerateAuditLog.php
UpdateAnalytics.php
```

---

# Jobs

```text
app/Jobs/

SendEmailJob.php
GenerateReportJob.php
ProcessAIRequestJob.php
```

---

# Notifications

```text
app/Notifications/

TicketCreatedNotification.php
InvoiceNotification.php
PaymentReceivedNotification.php
```

---

# Enums

```text
app/Enums/

TicketStatus.php
InvoiceStatus.php
PaymentStatus.php
UserRole.php
```

---

# Traits

```text
app/Traits/

BelongsToCompany.php
HasAuditLog.php
HasUuid.php
```

---

# Helpers

```text
app/Helpers/

DateHelper.php
CurrencyHelper.php
FileHelper.php
```

---

# Database

```text
database/

├── migrations/
├── seeders/
├── factories/
└── schema/
```

---

# Migrations

```text
database/migrations/

tenant/
core/
billing/
analytics/
audit/
```

---

# Seeders

```text
database/seeders/

RoleSeeder.php
PermissionSeeder.php
PlanSeeder.php
CompanySeeder.php
```

---

# Routes

```text
routes/

web.php
api.php
tenant.php
admin.php
```

---

# Resources

```text
resources/

├── views/
├── js/
├── css/
├── lang/
└── components/
```

---

# Views

```text
resources/views/

layouts/
auth/
dashboard/
companies/
customers/
tickets/
repairs/
inventory/
reports/
analytics/
settings/
```

---

# Dashboard Structure

```text
resources/views/dashboard/

super-admin/
owner/
manager/
technician/
customer/
```

---

# Storage

```text
storage/

app/
framework/
logs/
private/
public/
```

---

# Tests

```text
tests/

├── Unit/
├── Feature/
├── Integration/
└── Architecture/
```

---

# AWS Structure

```text
AWS

EC2
RDS PostgreSQL
Redis
S3
SES
SNS
CloudWatch
Route53
```

---

# IA Structure

```text
AI

Groq
OpenAI
Claude
Local LLM

Prompt Engine
Context Engine
RAG Engine
```

---

# Multi-Tenant Layer

```text
Tenant Resolver
Tenant Middleware
Tenant Policies
Tenant Cache
Tenant Storage
```

---

# Seguridad

```text
Authentication
Authorization
Permissions
Policies
Audit Logs
2FA
Rate Limiting
```

---

# Resultado Esperado

La estructura definida permitirá que IAtechs Pro evolucione como una plataforma SaaS Enterprise de nivel empresarial, soportando crecimiento modular, múltiples empresas, inteligencia artificial, integración AWS y futuras expansiones sin necesidad de reestructurar el proyecto.
