# Module Specification

# IAtechs Pro

## Módulo: Reports

---

# Objetivo

Centralizar la generación de reportes empresariales para monitorear el desempeño operativo, financiero, comercial y técnico de IAtechs Pro.

---

# Nombre Técnico

Reports

---

# Tabla Principal

reports

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
* Users

---

# Descripción

El módulo Reports permite generar informes dinámicos y exportables a partir de la información registrada en todos los módulos de IAtechs Pro.

Los reportes podrán visualizarse en pantalla, exportarse o programarse automáticamente.

---

# Tipos de Reportes

## Operational

```text
operational
```

---

## Financial

```text
financial
```

---

## Commercial

```text
commercial
```

---

## Technical

```text
technical
```

---

## Inventory

```text
inventory
```

---

## Executive

```text
executive
```

---

# Tabla reports

| Campo        | Tipo      |
| ------------ | --------- |
| id           | bigint    |
| company_id   | bigint    |
| report_name  | string    |
| report_type  | string    |
| filters      | json      |
| generated_by | bigint    |
| file_path    | string    |
| generated_at | timestamp |
| created_at   | timestamp |
| updated_at   | timestamp |

---

# Tabla scheduled_reports

| Campo          | Tipo      |
| -------------- | --------- |
| id             | bigint    |
| company_id     | bigint    |
| report_type    | string    |
| frequency      | string    |
| recipients     | json      |
| next_execution | datetime  |
| is_active      | boolean   |
| created_at     | timestamp |

---

# Frecuencias

## Daily

```text
daily
```

---

## Weekly

```text
weekly
```

---

## Monthly

```text
monthly
```

---

## Quarterly

```text
quarterly
```

---

## Yearly

```text
yearly
```

---

# Formatos de Exportación

## PDF

```text
pdf
```

---

## Excel

```text
xlsx
```

---

## CSV

```text
csv
```

---

## JSON

```text
json
```

---

# Migración Oficial Reports

```php
Schema::create('reports', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->string('report_name');

    $table->string('report_type');

    $table->json('filters')
        ->nullable();

    $table->foreignId('generated_by')
        ->constrained('users');

    $table->string('file_path')
        ->nullable();

    $table->timestamp('generated_at')
        ->nullable();

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
public function generatedBy()
{
    return $this->belongsTo(User::class, 'generated_by');
}
```

---

# Modelo

```text
app/Models/Report.php
```

---

# Repository

```text
app/Repositories/ReportRepository.php
```

---

# Service

```text
app/Services/ReportService.php
```

---

# Responsabilidades

* Generar reportes.
* Exportar información.
* Programar reportes automáticos.
* Consolidar indicadores.
* Crear reportes personalizados.
* Compartir reportes.
* Gestionar históricos.

---

# Policy

```text
ReportPolicy
```

---

# Permisos

```text
reports.view
reports.generate
reports.export
reports.schedule
reports.delete
reports.executive
```

---

# Endpoints Web

```http
GET     /reports
POST    /reports/generate
GET     /reports/{id}
GET     /reports/{id}/download
DELETE  /reports/{id}

GET     /reports/scheduled
POST    /reports/scheduled
```

---

# Endpoints API

```http
GET     /api/v1/reports
POST    /api/v1/reports/generate
GET     /api/v1/reports/{id}
GET     /api/v1/reports/{id}/download
```

---

# Reportes Operativos

## Tickets

```text
Tickets abiertos
Tickets cerrados
Tickets por técnico
Tiempo promedio de atención
```

---

## Reparaciones

```text
Reparaciones finalizadas
Retrabajos
Tiempo promedio de reparación
```

---

## Órdenes de Trabajo

```text
Órdenes completadas
Órdenes pendientes
Cumplimiento SLA
```

---

# Reportes Financieros

## Facturación

```text
Ventas diarias
Ventas mensuales
Facturación por sucursal
```

---

## Pagos

```text
Pagos recibidos
Pagos pendientes
Cartera vencida
```

---

## Compras

```text
Compras por proveedor
Costos operativos
Margen bruto
```

---

# Reportes de Inventario

```text
Stock actual
Productos críticos
Movimientos de inventario
Rotación de inventario
```

---

# Reportes Comerciales

```text
Clientes nuevos
Clientes recurrentes
Top clientes
Cotizaciones aprobadas
```

---

# Reportes Técnicos

```text
Productividad por técnico
Tiempo promedio de respuesta
SLA cumplidos
Garantías ejecutadas
```

---

# Reportes Ejecutivos

```text
Ingresos
Gastos
Rentabilidad
KPI generales
Crecimiento empresarial
```

---

# Flujo de Negocio

## Generar Reporte

```text
Usuario
   ↓
Selecciona Reporte
   ↓
Aplicar Filtros
   ↓
Generar
   ↓
Exportar
```

---

## Reporte Automático

```text
Programación
      ↓
Job
      ↓
Generar Archivo
      ↓
Enviar Correo
```

---

# Reglas de Negocio

## Regla 1

Todo reporte pertenece a una empresa.

---

## Regla 2

Los reportes ejecutivos solo podrán ser vistos por administradores autorizados.

---

## Regla 3

Toda exportación debe quedar auditada.

---

## Regla 4

Los reportes programados se ejecutarán mediante colas.

---

## Regla 5

Los filtros deben respetar el alcance de permisos del usuario.

---

## Regla 6

Los archivos generados podrán almacenarse en AWS S3.

---

# Auditoría

Registrar:

```text
Reporte generado
Reporte exportado
Reporte descargado
Reporte programado
Reporte eliminado
```

---

# Eventos

```text
ReportGenerated
ReportExported
ScheduledReportCreated
ScheduledReportExecuted
```

---

# Jobs

```text
GenerateReportJob
ExportReportJob
SendScheduledReportJob
CleanupOldReportsJob
```

---

# Testing

## Unit Tests

```text
ReportServiceTest
ReportExportTest
ReportFilterTest
```

---

## Feature Tests

```text
GenerateReportTest
DownloadReportTest
ScheduledReportTest
ExecutiveReportTest
```

---

# KPI del Módulo

* Reportes generados.
* Reportes descargados.
* Tiempo promedio de generación.
* Reportes programados activos.
* Reportes ejecutivos consultados.
* Exportaciones realizadas.

---

# Integración con Otros Módulos

```text
Tickets
Diagnostics
Repairs
Inventory
Purchases
Invoices
Payments
WorkOrders
Customers
Analytics
AuditLogs
Notifications
```

---

# Resultado Esperado

El módulo Reports permitirá que IAtechs Pro disponga de inteligencia operativa y financiera en tiempo real, facilitando la toma de decisiones estratégicas mediante reportes avanzados, exportables y automatizados.
