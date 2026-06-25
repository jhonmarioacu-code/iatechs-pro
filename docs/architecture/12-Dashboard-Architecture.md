# IAtechs Pro

# Architecture

## 12-Dashboard-Architecture.md

---

# Objetivo

Definir la arquitectura oficial de Dashboards de IAtechs Pro para proporcionar visualización estratégica, operativa y analítica en tiempo real para cada rol dentro de la plataforma.

---

# Filosofía

Todo usuario debe visualizar únicamente la información relevante para:

* Su empresa.
* Su rol.
* Sus permisos.
* Su contexto operativo.

---

# Arquitectura General

```text
Dashboard Layer

├── SaaS Dashboard
├── Company Dashboard
├── CRM Dashboard
├── Service Desk Dashboard
├── Inventory Dashboard
├── Accounting Dashboard
├── AI Dashboard
├── Reports Dashboard
└── Executive Dashboard
```

---

# Dashboard SaaS

## Acceso

```text
super_admin
```

---

## Objetivo

Administrar toda la plataforma SaaS.

---

## KPIs

```text
Empresas Activas

Empresas Suspendidas

Usuarios Totales

MRR

ARR

Consumo IA

Tickets Totales

Facturación Total
```

---

# Company Dashboard

## Acceso

```text
owner
manager
```

---

## KPIs

```text
Clientes

Equipos

Tickets

Facturas

Inventario

Ventas

Pagos
```

---

# CRM Dashboard

## Acceso

```text
manager
sales
owner
```

---

## Widgets

```text
Leads Nuevos

Leads Convertidos

Oportunidades

Pipeline

Valor del Pipeline

Conversión
```

---

# Service Desk Dashboard

## Acceso

```text
manager
technician
```

---

## Widgets

```text
Tickets Abiertos

Tickets Cerrados

Tickets Pendientes

Tiempo Promedio

SLA

Reparaciones
```

---

# Inventory Dashboard

## Acceso

```text
manager
inventory
owner
```

---

## Widgets

```text
Productos

Stock Bajo

Entradas

Salidas

Compras

Valor Inventario
```

---

# Accounting Dashboard

## Acceso

```text
accountant
manager
owner
```

---

## Widgets

```text
Ingresos

Gastos

Utilidad

Cuentas por Cobrar

Cuentas por Pagar

Flujo de Caja
```

---

# AI Dashboard

## Acceso

```text
owner
manager
super_admin
```

---

## Widgets

```text
Conversaciones

Tokens Consumidos

Costo IA

Proveedores IA

Modelos IA

Prompts Utilizados
```

---

# Knowledge Base Dashboard

## Widgets

```text
Artículos

Consultas

Documentos

Categorías

Artículos Más Vistos
```

---

# Reports Dashboard

## Widgets

```text
Reportes Generados

Exportaciones

PDF

Excel

Indicadores
```

---

# Executive Dashboard

## Objetivo

Visualización ejecutiva empresarial.

---

## KPIs

```text
Ventas

Facturación

Rentabilidad

Clientes

Tickets

IA

Inventario

Productividad
```

---

# Dashboard Widgets

## KPI Widget

```text
Número Principal
Variación
Tendencia
```

---

## Chart Widget

```text
Line Chart

Bar Chart

Pie Chart

Area Chart
```

---

## Table Widget

```text
Top Clientes

Top Productos

Top Técnicos

Top Tickets
```

---

## Activity Widget

```text
Actividad Reciente
```

---

## AI Widget

```text
Recomendaciones

Análisis IA

Insights
```

---

# Diseño

## Grid

```text
12 Column Layout
```

---

## Responsive

```text
Desktop

Tablet

Mobile
```

---

# Actualización

## Tiempo Real

Tecnología:

```text
Laravel Reverb

WebSockets

Broadcasting
```

---

# Seguridad

Todo dashboard deberá respetar:

```text
Tenant

Roles

Permissions

Policies
```

---

# Cache

## Redis

Formato:

```text
dashboard:{company_id}
dashboard:kpi:{company_id}
dashboard:analytics:{company_id}
```

---

# Analytics

## Métricas

```text
Diarias

Semanales

Mensuales

Anuales
```

---

# Exportación

Formatos:

```text
PDF

Excel

CSV
```

---

# Auditoría

Registrar:

```text
Dashboard Access

Dashboard Export

Widget Load

Analytics Request
```

---

# Testing

## Unit Tests

```text
DashboardServiceTest

DashboardWidgetTest
```

---

## Feature Tests

```text
DashboardAccessTest

DashboardTenantTest

DashboardPermissionTest
```

---

# Reglas de Negocio

## Regla 1

Todo dashboard debe respetar company_id.

---

## Regla 2

Todo widget debe respetar permisos.

---

## Regla 3

Toda métrica debe ser auditable.

---

## Regla 4

Todo dashboard debe ser responsive.

---

## Regla 5

Los dashboards ejecutivos deben utilizar datos agregados.

---

# Resultado Esperado

IAtechs Pro dispondrá de un sistema de dashboards empresariales centralizados, multi-tenant, escalables y en tiempo real, proporcionando visibilidad completa del negocio mediante KPIs, analíticas y widgets especializados para cada área de operación.
