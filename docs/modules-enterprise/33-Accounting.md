# IAtechs Pro

# Module Specification

## 33-Accounting

---

# Objetivo

Administrar la contabilidad financiera de las empresas registradas en IAtechs Pro mediante cuentas contables, asientos contables, centros de costos, períodos fiscales y generación de estados financieros.

---

# Nombre Técnico

```text
Accounting
```

---

# Descripción

El módulo Accounting permite controlar la contabilidad empresarial bajo principios contables estándar.

Permite:

* Gestionar Plan de Cuentas.
* Registrar Asientos Contables.
* Gestionar Centros de Costos.
* Gestionar Períodos Fiscales.
* Generar Libro Diario.
* Generar Libro Mayor.
* Generar Balance General.
* Generar Estado de Resultados.

---

# Componentes

## Accounts

Plan de cuentas.

---

## Journal Entries

Asientos contables.

---

## Journal Entry Lines

Movimientos débito/crédito.

---

## Cost Centers

Centros de costos.

---

## Fiscal Periods

Períodos fiscales.

---

# Tablas

```text
accounts

journal_entries

journal_entry_lines

cost_centers

fiscal_periods
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Manager

Acceso administrativo.

---

## Accountant

Acceso contable.

---

# Tipos de Cuenta

```text
asset

liability

equity

income

expense
```

---

# Estados de Asiento

```text
draft

posted

reversed
```

---

# Relaciones

## Account → JournalEntryLines

```text
1:N
```

---

## JournalEntry → JournalEntryLines

```text
1:N
```

---

## CostCenter → JournalEntries

```text
1:N
```

---

## FiscalPeriod → JournalEntries

```text
1:N
```

---

# Campos Principales

## Account

```text
id

company_id

code

name

type

parent_id

is_active
```

---

## JournalEntry

```text
id

company_id

cost_center_id

fiscal_period_id

entry_number

entry_date

description

status
```

---

## JournalEntryLine

```text
id

journal_entry_id

account_id

debit

credit

description
```

---

## CostCenter

```text
id

company_id

name

description

is_active
```

---

## FiscalPeriod

```text
id

company_id

name

start_date

end_date

status
```

---

# Modelos

## Account

```text
app/Domains/Accounting/Models/Account.php
```

---

## JournalEntry

```text
app/Domains/Accounting/Models/JournalEntry.php
```

---

## JournalEntryLine

```text
app/Domains/Accounting/Models/JournalEntryLine.php
```

---

## CostCenter

```text
app/Domains/Accounting/Models/CostCenter.php
```

---

## FiscalPeriod

```text
app/Domains/Accounting/Models/FiscalPeriod.php
```

---

# Repositories

```text
AccountRepository.php

JournalEntryRepository.php
```

---

# Service

```text
AccountingService.php
```

---

# Responsabilidades

* Crear cuentas.
* Crear asientos.
* Publicar asientos.
* Revertir asientos.
* Validar balance contable.
* Generar estados financieros.

---

# Controller

```text
AccountingController.php
```

---

# Requests

```text
StoreAccountRequest.php

UpdateAccountRequest.php

StoreJournalEntryRequest.php

UpdateJournalEntryRequest.php
```

---

# Resources

```text
AccountResource.php

JournalEntryResource.php
```

---

# Policy

```text
AccountingPolicy.php
```

---

# Permisos

```text
accounting.view

accounting.create

accounting.update

accounting.delete

accounting.post

accounting.reverse

accounting.export
```

---

# Endpoints Web

```http
GET     /accounting

GET     /accounting/accounts

POST    /accounting/accounts

GET     /accounting/journal-entries

POST    /accounting/journal-entries
```

---

# Endpoints API

```http
GET     /api/v1/accounting/accounts

POST    /api/v1/accounting/accounts

PUT     /api/v1/accounting/accounts/{id}

GET     /api/v1/accounting/journal-entries

POST    /api/v1/accounting/journal-entries

PUT     /api/v1/accounting/journal-entries/{id}
```

---

# Workflow

## Asiento Contable

```text
Draft
 ↓
Validation
 ↓
Posted
 ↓
Ledger
```

---

# Regla Contable

Todo asiento debe cumplir:

```text
Total Debits
=
Total Credits
```

---

# Integraciones

## Invoices

```text
Invoice
 ↓
Journal Entry
```

---

## Payments

```text
Payment
 ↓
Journal Entry
```

---

## Purchases

```text
Purchase
 ↓
Journal Entry
```

---

## Inventory

```text
Inventory Movement
 ↓
Accounting Impact
```

---

# Reportes Financieros

## Balance General

```text
Assets

Liabilities

Equity
```

---

## Estado de Resultados

```text
Income

Expenses

Net Profit
```

---

## Libro Diario

```text
Journal Entries
```

---

## Libro Mayor

```text
Account Movements
```

---

# Dashboard

Widgets:

```text
Ingresos

Gastos

Utilidad

Cuentas por Cobrar

Cuentas por Pagar

Flujo de Caja
```

---

# Analytics

KPIs:

```text
Revenue

Expenses

Profit

Cash Flow

Outstanding Receivables

Outstanding Payables
```

---

# AI Integration

Funciones:

```text
Financial Analysis

Profitability Analysis

Expense Analysis

Accounting Insights
```

---

# Auditoría

Eventos:

```text
Account Created

Account Updated

Journal Entry Created

Journal Entry Posted

Journal Entry Reversed

Financial Report Generated
```

---

# Testing

## Unit Tests

```text
AccountRepositoryTest

JournalEntryRepositoryTest

AccountingServiceTest
```

---

## Feature Tests

```text
CreateJournalEntryTest

PostJournalEntryTest

FinancialReportTest
```

---

# Reglas de Negocio

## Regla 1

Toda cuenta pertenece a una empresa.

---

## Regla 2

Todo asiento debe balancear débitos y créditos.

---

## Regla 3

No se puede publicar un asiento desbalanceado.

---

## Regla 4

No se puede registrar un asiento fuera de un período fiscal abierto.

---

## Regla 5

Toda consulta debe respetar company_id.

---

# KPI del Módulo

* Ingresos.
* Gastos.
* Utilidad.
* Flujo de Caja.
* Cuentas por Cobrar.
* Cuentas por Pagar.
* Margen Neto.

---

# Resultado Esperado

El módulo Accounting permitirá administrar la contabilidad financiera completa de IAtechs Pro, proporcionando control sobre cuentas, asientos, centros de costos y períodos fiscales, con integración nativa a Facturación, Inventario, Compras, Reportes e Inteligencia Artificial.
