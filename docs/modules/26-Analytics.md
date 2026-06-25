# Module Specification

# IAtechs Pro

## Módulo: Analytics

---

# Objetivo

Proporcionar análisis avanzados, métricas empresariales, dashboards ejecutivos y herramientas de Business Intelligence para la toma de decisiones estratégicas.

---

# Nombre Técnico

Analytics

---

# Tabla Principal

analytics_dashboards

---

# Dependencias

* Companies
* Branches
* Customers
* Tickets
* Diagnostics
* Repairs
* Inventory
* Purchases
* Invoices
* Payments
* WorkOrders
* ServiceContracts
* Reports

---

# Descripción

El módulo Analytics consolida información proveniente de todos los módulos del sistema para mostrar indicadores clave de rendimiento (KPIs), tendencias, comparativos históricos y proyecciones de negocio.

---

# Tipos de Dashboard

## Executive Dashboard

```text
executive
```

Vista gerencial.

---

## Financial Dashboard

```text
financial
```

Análisis financiero.

---

## Technical Dashboard

```text
technical
```

Indicadores operativos.

---

## Commercial Dashboard

```text
commercial
```

Ventas y clientes.

---

## Inventory Dashboard

```text
inventory
```

Inventario y compras.

---

# Tabla analytics_dashboards

| Campo          | Tipo      |
| -------------- | --------- |
| id             | bigint    |
| company_id     | bigint    |
| dashboard_name | string    |
| dashboard_type | string    |
| configuration  | json      |
| created_by     | bigint    |
| created_at     | timestamp |
| updated_at     | timestamp |

---

# Tabla analytics_metrics

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| metric_key    | string    |
| metric_name   | string    |
| metric_value  | decimal   |
| calculated_at | timestamp |
| created_at    | timestamp |

---

# Tabla analytics_snapshots

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| snapshot_date | datetime  |
| data          | json      |
| created_at    | timestamp |

---

# Migración Oficial Analytics

```php
Schema::create('analytics_dashboards', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->string('dashboard_name');

    $table->string('dashboard_type');

    $table->json('configuration')
        ->nullable();

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();
});
```

---

# Relaciones

## Company

```php
public function company()
{
    return $this->belongsTo(Company::class);
}
```

---

## User

```php
public function createdBy()
{
    return $this->belongsTo(User::class, 'created_by');
}
```

---

# Modelo

```text
app/Models/AnalyticsDashboard.php
```

---

# Repository

```text
app/Repositories/AnalyticsRepository.php
```

---

# Service

```text
app/Services/AnalyticsService.php
```

---

# Responsabilidades

* Calcular KPIs.
* Generar dashboards.
* Analizar tendencias.
* Comparar períodos.
* Detectar anomalías.
* Generar métricas automáticas.
* Soportar análisis predictivo.

---

# Policy

```text
AnalyticsPolicy
```

---

# Permisos

```text
analytics.view
analytics.dashboard
analytics.export
analytics.executive
analytics.predictive
analytics.manage
```

---

# Endpoints Web

```http
GET     /analytics
GET     /analytics/executive
GET     /analytics/financial
GET     /analytics/technical
GET     /analytics/commercial
GET     /analytics/inventory
```

---

# Endpoints API

```http
GET     /api/v1/analytics
GET     /api/v1/analytics/kpis
GET     /api/v1/analytics/dashboards
GET     /api/v1/analytics/trends
```

---

# KPIs Financieros

```text
Ingresos Totales
Ingresos Mensuales
Facturación Promedio
Margen Bruto
Rentabilidad
Pagos Pendientes
Cartera Vencida
```

---

# KPIs Operativos

```text
Tickets Abiertos
Tickets Cerrados
Tiempo Promedio de Atención
Órdenes Completadas
Cumplimiento SLA
```

---

# KPIs Técnicos

```text
Productividad por Técnico
Tiempo de Reparación
Garantías Ejecutadas
Retrabajos
```

---

# KPIs Comerciales

```text
Clientes Nuevos
Clientes Activos
Tasa de Conversión
Cotizaciones Aprobadas
```

---

# KPIs Inventario

```text
Rotación de Inventario
Productos Críticos
Valor Inventario
Costo de Compras
```

---

# Dashboards

## Dashboard CEO

```text
Ingresos
Rentabilidad
Crecimiento
Top Clientes
Top Servicios
```

---

## Dashboard Operativo

```text
Tickets
Work Orders
SLA
Técnicos
```

---

## Dashboard Financiero

```text
Facturación
Pagos
Cartera
Gastos
```

---

# Análisis Predictivo

## Predicción de Ventas

```text
Forecast de ingresos
```

---

## Predicción de Demanda

```text
Consumo de repuestos
```

---

## Predicción Operativa

```text
Carga de trabajo técnica
```

---

# Flujo de Negocio

## Generación KPI

```text
Datos Sistema
      ↓
Analytics Engine
      ↓
Calcular KPI
      ↓
Dashboard
```

---

## Dashboard Ejecutivo

```text
Módulos
   ↓
Analytics
   ↓
Indicadores
   ↓
CEO
```

---

# Reglas de Negocio

## Regla 1

Toda métrica pertenece a una empresa.

---

## Regla 2

Los dashboards ejecutivos requieren permisos especiales.

---

## Regla 3

Las métricas deben actualizarse automáticamente.

---

## Regla 4

Toda exportación debe quedar auditada.

---

## Regla 5

Los snapshots históricos no podrán modificarse.

---

## Regla 6

Las predicciones utilizarán datos históricos reales.

---

# Auditoría

Registrar:

```text
Dashboard creado
Dashboard consultado
Métrica calculada
Exportación realizada
Snapshot generado
```

---

# Eventos

```text
DashboardCreated
MetricCalculated
AnalyticsExported
SnapshotGenerated
```

---

# Jobs

```text
CalculateKpisJob
GenerateAnalyticsSnapshotJob
RefreshDashboardsJob
GenerateForecastJob
```

---

# Testing

## Unit Tests

```text
AnalyticsServiceTest
KpiCalculationTest
ForecastTest
```

---

## Feature Tests

```text
ViewDashboardTest
ExportAnalyticsTest
ExecutiveAnalyticsTest
GenerateSnapshotTest
```

---

# KPI del Módulo

```text
Dashboards activos
Métricas calculadas
Tiempo de actualización
Consultas ejecutivas
Exportaciones realizadas
Precisión de predicciones
```

---

# Integración con Otros Módulos

```text
Reports
Tickets
Diagnostics
Repairs
Inventory
Purchases
Invoices
Payments
WorkOrders
ServiceContracts
Notifications
AuditLogs
```

---

# Resultado Esperado

El módulo Analytics permitirá que IAtechs Pro disponga de una plataforma de Business Intelligence empresarial, proporcionando información estratégica en tiempo real, dashboards ejecutivos, análisis de rendimiento y capacidades predictivas para impulsar el crecimiento del negocio.
