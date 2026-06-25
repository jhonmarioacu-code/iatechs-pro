# IAtechs Pro

# Architecture

## 02-Database-Architecture.md

---

# Objetivo

Definir la arquitectura de base de datos oficial de IAtechs Pro para garantizar escalabilidad, rendimiento, seguridad, multi-tenancy y crecimiento empresarial.

---

# Motor de Base de Datos

## Principal

```text
PostgreSQL 17
```

---

# Servicios AWS

```text
Amazon RDS PostgreSQL
Read Replicas
Automated Backups
Point-In-Time Recovery
Multi-AZ
```

---

# Principios de Diseño

* Multi-Tenant
* Alta disponibilidad
* Escalable
* Auditada
* Compatible con IA
* Preparada para millones de registros
* Optimizada para reportes

---

# Estrategia Multi-Tenant

## Tenant por Empresa

Cada registro empresarial deberá contener:

```sql
company_id
```

---

# Ejemplo

```text
companies
    |
    ├── users
    ├── customers
    ├── devices
    ├── tickets
    ├── repairs
    ├── invoices
    └── inventory
```

---

# Regla Principal

```text
Todo dato empresarial debe pertenecer a una empresa.
```

---

# Tablas Core

```text
companies
subscriptions
plans
users
roles
permissions
branches
customers
devices
tickets
diagnostics
quotes
repairs
inventory
suppliers
purchases
purchase_orders
goods_receipts
invoices
payments
warranties
service_contracts
work_orders
technician_schedules
notifications
reports
analytics
audit_logs
files
ai_conversations
system_settings
```

---

# Convenciones de Tablas

## Primary Key

```sql
id BIGINT
```

---

## Foreign Keys

```sql
company_id
user_id
customer_id
ticket_id
```

---

## Timestamps

```sql
created_at
updated_at
```

---

## Soft Deletes

```sql
deleted_at
```

---

# Índices Obligatorios

## Tenant

```sql
INDEX(company_id)
```

---

## Relaciones

```sql
INDEX(user_id)
INDEX(customer_id)
INDEX(ticket_id)
```

---

## Fechas

```sql
INDEX(created_at)
INDEX(updated_at)
```

---

# Índices Compuestos

Ejemplo:

```sql
(company_id, status)
(company_id, created_at)
(company_id, customer_id)
```

---

# Categorías de Datos

## Core Data

```text
Companies
Users
Roles
Permissions
Branches
```

---

## Customer Data

```text
Customers
Devices
Tickets
Diagnostics
Repairs
```

---

## Financial Data

```text
Invoices
Payments
Purchases
Suppliers
```

---

## Analytics Data

```text
Reports
Analytics
Snapshots
KPIs
```

---

## AI Data

```text
AI Conversations
AI Messages
AI Automations
```

---

## Audit Data

```text
Audit Logs
Sessions
Exports
```

---

# Auditoría

## Tablas Auditables

```text
Users
Customers
Tickets
Repairs
Invoices
Payments
Inventory
SystemSettings
```

---

# Estrategia

```text
Old Values
New Values
User
IP
Timestamp
```

---

# Particionado

## Audit Logs

```text
Por Mes
```

Ejemplo:

```text
audit_logs_2026_01
audit_logs_2026_02
audit_logs_2026_03
```

---

## Analytics Snapshots

```text
Por Año
```

---

# Caché

## Redis

Uso:

```text
Configuraciones
Permisos
Dashboards
KPIs
Sesiones
```

---

# Archivos

No almacenar archivos en PostgreSQL.

Guardar:

```text
Ruta
Nombre
Checksum
Metadata
```

Archivo físico:

```text
AWS S3
```

---

# Seguridad

## Encriptar

```text
API Keys
Tokens
Secrets
SMTP Passwords
```

---

# Backups

## Frecuencia

```text
Diario
```

---

## Retención

```text
30 días
```

---

## AWS

```text
RDS Snapshots
```

---

# Replicación

## Producción

```text
1 Primary
2 Read Replicas
```

---

# Optimización

## EXPLAIN ANALYZE

Todas las consultas críticas deben validarse.

---

## Lazy Loading

Deshabilitado.

```php
Model::preventLazyLoading();
```

---

## Eager Loading

Obligatorio.

```php
with()
```

---

# Estrategia de Migraciones

```text
database/migrations/

core/
billing/
inventory/
tickets/
analytics/
audit/
ai/
settings/
```

---

# Seeders

```text
RoleSeeder
PermissionSeeder
PlanSeeder
SystemSettingSeeder
```

---

# Factories

```text
UserFactory
CompanyFactory
CustomerFactory
TicketFactory
InvoiceFactory
```

---

# Integridad Referencial

Todas las relaciones deben utilizar:

```php
->constrained()
->cascadeOnDelete()
```

cuando aplique.

---

# Escalabilidad

Objetivo inicial:

```text
100 Empresas
10.000 Usuarios
500.000 Tickets
5.000.000 Audit Logs
```

---

# Escalabilidad Futura

Objetivo Enterprise:

```text
10.000 Empresas
1.000.000 Usuarios
100.000.000 Tickets
```

---

# Monitoreo

## CloudWatch

```text
CPU
RAM
IOPS
Queries
Connections
```

---

# Métricas

```text
Slow Queries
Deadlocks
Connections
Cache Hit Ratio
```

---

# Resultado Esperado

La arquitectura de base de datos de IAtechs Pro deberá soportar crecimiento empresarial, operación multi-tenant, integridad de datos, auditoría completa, inteligencia artificial, reportes avanzados y alta disponibilidad sin requerir rediseños estructurales en futuras versiones.
