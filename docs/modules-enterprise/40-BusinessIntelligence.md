# IAtechs Pro

# Module Specification

## 40-BusinessIntelligence

---

# Objetivo

Centralizar la información estratégica de la empresa mediante dashboards ejecutivos, indicadores KPI, análisis de datos, métricas SaaS, forecasting e inteligencia artificial para apoyar la toma de decisiones empresariales.

---

# Nombre Técnico

```text
BusinessIntelligence
```

---

# Descripción

El módulo Business Intelligence permite consolidar información proveniente de todos los módulos de IAtechs Pro para generar análisis empresariales avanzados.

Permite:

* Dashboards ejecutivos.
* KPIs corporativos.
* Forecasting.
* Análisis financiero.
* Análisis operacional.
* Métricas SaaS.
* Data Warehouse.
* Insights generados por IA.

---

# Componentes

## Dashboards

Paneles de control.

---

## KPIs

Indicadores clave.

---

## Analytics

Analítica empresarial.

---

## Forecasting

Proyecciones.

---

## Data Warehouse

Almacenamiento analítico.

---

## AI Insights

Recomendaciones inteligentes.

---

# Tablas

```text
dashboards

dashboard_widgets

kpis

analytics_snapshots

forecast_models

ai_insights
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso ejecutivo.

---

## Manager

Acceso según permisos.

---

## Analyst

Acceso analítico.

---

# Tipos de Dashboard

```text
executive

financial

operational

sales

support

inventory

custom
```

---

# Relaciones

## Dashboard → Widgets

```text
1:N
```

---

## KPI → Analytics

```text
1:N
```

---

## Forecast → KPIs

```text
1:N
```

---

# Campos Principales

## Dashboard

```text
id

company_id

name

type

description

is_default
```

---

## Dashboard Widget

```text
id

dashboard_id

title

widget_type

position

configuration
```

---

## KPI

```text
id

company_id

name

code

category

target

current_value
```

---

## Analytics Snapshot

```text
id

company_id

period

metric

value

captured_at
```

---

## AI Insight

```text
id

company_id

title

description

severity

generated_at
```

---

# Modelos

## Dashboard

```text
app/Domains/BusinessIntelligence/Models/Dashboard.php
```

---

## DashboardWidget

```text
app/Domains/BusinessIntelligence/Models/DashboardWidget.php
```

---

## KPI

```text
app/Domains/BusinessIntelligence/Models/KPI.php
```

---

## AnalyticsSnapshot

```text
app/Domains/BusinessIntelligence/Models/AnalyticsSnapshot.php
```

---

## AIInsight

```text
app/Domains/BusinessIntelligence/Models/AIInsight.php
```

---

# Repositories

```text
DashboardRepository.php

AnalyticsRepository.php

KPIRepository.php
```

---

# Service

```text
BusinessIntelligenceService.php
```

---

# Responsabilidades

* Consolidar información.
* Generar KPIs.
* Construir dashboards.
* Generar forecasting.
* Detectar anomalías.
* Generar insights IA.

---

# Controller

```text
BusinessIntelligenceController.php
```

---

# Requests

```text
StoreDashboardRequest.php

UpdateDashboardRequest.php

StoreKPIRequest.php
```

---

# Resources

```text
DashboardResource.php

KPIResource.php
```

---

# Policy

```text
BusinessIntelligencePolicy.php
```

---

# Permisos

```text
bi.view

bi.create

bi.update

bi.delete

bi.analytics

bi.forecasting

bi.export
```

---

# Endpoints Web

```http
GET     /business-intelligence

GET     /dashboards

POST    /dashboards

GET     /kpis

POST    /kpis
```

---

# Endpoints API

```http
GET     /api/v1/analytics

GET     /api/v1/kpis

GET     /api/v1/dashboards

GET     /api/v1/forecasts

GET     /api/v1/ai-insights
```

---

# Integración de Módulos

## CRM

```text
Leads

Conversiones

Ventas
```

---

## Accounting

```text
Ingresos

Gastos

Utilidad

Flujo de caja
```

---

## Human Resources

```text
Productividad

Rotación

Asistencia
```

---

## Projects

```text
Avance

Costos

Horas
```

---

## Inventory

```text
Rotación

Valorización

Stock crítico
```

---

## Support

```text
Tickets

SLA

Satisfacción
```

---

# Dashboard Ejecutivo

Widgets:

```text
Ingresos

Utilidad

Tickets

Clientes

Inventario

Proyectos

Consumo IA
```

---

# Dashboard Financiero

Widgets:

```text
Revenue

Expenses

Cash Flow

Accounts Receivable

Accounts Payable
```

---

# Dashboard Operacional

Widgets:

```text
Tickets

Repairs

Technicians

Inventory

Projects
```

---

# Métricas SaaS

KPIs:

```text
MRR

ARR

ARPU

Churn Rate

LTV

CAC

Active Companies

Active Users
```

---

# Forecasting

Modelos:

```text
Sales Forecast

Revenue Forecast

Demand Forecast

Ticket Forecast

Inventory Forecast
```

---

# Inteligencia Artificial

## AI Insights

Ejemplos:

```text
Disminución de ventas

Aumento de tickets

Stock crítico

Clientes en riesgo

Incremento de costos

Predicción de churn
```

---

# Data Warehouse

Fuentes:

```text
CRM

Accounting

Inventory

Projects

Support

HR

AI
```

---

# Dashboard Multi-Tenant

Toda información deberá filtrarse mediante:

```sql
company_id
```

---

# Analytics

KPIs Globales:

```text
Revenue

Profit

Customer Growth

Inventory Turnover

Ticket Resolution

Project Completion

AI Usage
```

---

# Auditoría

Eventos:

```text
Dashboard Created

KPI Created

Forecast Generated

Insight Generated

Dashboard Exported
```

---

# Testing

## Unit Tests

```text
DashboardRepositoryTest

KPIRepositoryTest

AnalyticsServiceTest
```

---

## Feature Tests

```text
DashboardViewTest

KPITrackingTest

ForecastGenerationTest
```

---

# Reglas de Negocio

## Regla 1

Todo KPI pertenece a una empresa.

---

## Regla 2

Todo dashboard debe respetar company_id.

---

## Regla 3

Toda métrica debe ser auditable.

---

## Regla 4

Los insights IA deben almacenarse.

---

## Regla 5

Los dashboards ejecutivos solo pueden ser accedidos por usuarios autorizados.

---

# KPI del Módulo

```text
MRR

ARR

Profit Margin

Customer Retention

Inventory Turnover

Project Success Rate

Ticket Resolution Rate

AI Consumption
```

---

# Resultado Esperado

El módulo Business Intelligence convertirá IAtechs Pro en una plataforma Enterprise Data-Driven, proporcionando dashboards ejecutivos, analítica avanzada, forecasting, métricas SaaS e inteligencia artificial para la toma de decisiones estratégicas en tiempo real.
