# IAtechs Pro

# Module Specification

## 31-CRM

---

# Objetivo

Administrar todo el ciclo comercial de la empresa desde la captación de prospectos hasta la conversión en clientes.

El módulo CRM permitirá controlar Leads, Oportunidades, Actividades, Seguimientos, Notas y métricas comerciales.

---

# Nombre Técnico

```text
CRM
```

---

# Descripción

El módulo CRM centraliza la gestión comercial de IAtechs Pro.

Permite:

* Captar Leads.
* Gestionar Oportunidades.
* Registrar Actividades.
* Registrar Notas.
* Gestionar Pipeline Comercial.
* Medir Conversión.
* Integrar Facturación.
* Integrar IA.

---

# Componentes

## Leads

Prospectos comerciales.

---

## Opportunities

Oportunidades de venta.

---

## Activities

Llamadas, reuniones y tareas.

---

## Notes

Observaciones comerciales.

---

# Tablas

```text
crm_leads

crm_opportunities

crm_activities

crm_notes
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

Acceso completo.

---

## Sales

Acceso comercial.

---

# Lead

## Estados

```text
new

contacted

qualified

unqualified

converted
```

---

# Opportunity

## Estados

```text
open

proposal

negotiation

won

lost
```

---

# Relaciones

## Lead

```text
Lead

1:N Activities

1:N Notes

1:N Opportunities
```

---

## Opportunity

```text
Opportunity

N:1 Lead

1:N Activities

1:N Notes
```

---

# Campos Principales

## Lead

```text
id

company_id

owner_id

name

email

phone

source

status

created_at
```

---

## Opportunity

```text
id

company_id

lead_id

owner_id

title

value

status

expected_close_date
```

---

# Modelo

## Lead

```text
app/Domains/CRM/Models/Lead.php
```

---

## Opportunity

```text
app/Domains/CRM/Models/Opportunity.php
```

---

## Activity

```text
app/Domains/CRM/Models/Activity.php
```

---

## Note

```text
app/Domains/CRM/Models/Note.php
```

---

# Repositories

```text
LeadRepository.php

OpportunityRepository.php
```

---

# Services

```text
CRMService.php
```

---

# Responsabilidades

* Crear Leads.
* Convertir Leads.
* Crear Oportunidades.
* Actualizar Pipeline.
* Generar métricas.
* Automatizar seguimientos.

---

# Controller

```text
CRMController.php
```

---

# Requests

```text
StoreLeadRequest.php

UpdateLeadRequest.php

StoreOpportunityRequest.php

UpdateOpportunityRequest.php
```

---

# Resources

```text
LeadResource.php

OpportunityResource.php
```

---

# Policy

```text
CRMPolicy.php
```

---

# Permisos

```text
crm.view

crm.create

crm.update

crm.delete

crm.convert

crm.export
```

---

# Endpoints Web

```http
GET     /crm

GET     /crm/leads

POST    /crm/leads

GET     /crm/opportunities

POST    /crm/opportunities
```

---

# Endpoints API

```http
GET     /api/v1/crm/leads

POST    /api/v1/crm/leads

PUT     /api/v1/crm/leads/{id}

GET     /api/v1/crm/opportunities

POST    /api/v1/crm/opportunities

PUT     /api/v1/crm/opportunities/{id}
```

---

# Workflow

## Lead

```text
Lead
 ↓
Contacted
 ↓
Qualified
 ↓
Converted
 ↓
Customer
```

---

## Opportunity

```text
Open
 ↓
Proposal
 ↓
Negotiation
 ↓
Won
```

---

# Integraciones

## Customers

```text
Lead → Customer
```

---

## Invoices

```text
Opportunity → Invoice
```

---

## AI Assistant

Funciones:

```text
Lead Scoring

Sales Suggestions

Opportunity Analysis

Commercial Insights
```

---

# Dashboard

Widgets:

```text
Leads

Opportunities

Pipeline

Conversion Rate

Sales Forecast
```

---

# Analytics

KPIs:

```text
Leads Created

Leads Converted

Opportunities Won

Opportunities Lost

Pipeline Value

Conversion Rate
```

---

# Auditoría

Eventos:

```text
Lead Created

Lead Updated

Lead Converted

Opportunity Created

Opportunity Won

Opportunity Lost
```

---

# Testing

## Unit Tests

```text
LeadRepositoryTest

OpportunityRepositoryTest

CRMServiceTest
```

---

## Feature Tests

```text
CreateLeadTest

ConvertLeadTest

OpportunityWorkflowTest
```

---

# Reglas de Negocio

## Regla 1

Todo Lead pertenece a una empresa.

---

## Regla 2

Toda Opportunity debe originarse desde un Lead.

---

## Regla 3

Un Lead convertido no puede volver a estado inicial.

---

## Regla 4

Toda Opportunity debe tener propietario.

---

## Regla 5

Todo registro debe respetar company_id.

---

# KPI del Módulo

* Leads nuevos.
* Leads convertidos.
* Oportunidades abiertas.
* Oportunidades ganadas.
* Valor Pipeline.
* Conversión Comercial.

---

# Resultado Esperado

El módulo CRM permitirá administrar integralmente el proceso comercial de IAtechs Pro, proporcionando control total sobre prospectos, oportunidades, actividades y métricas de ventas, manteniendo integración nativa con Clientes, Facturación, Reportes e Inteligencia Artificial.
