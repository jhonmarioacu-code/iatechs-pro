# Module Specification

# IAtechs Pro

## Módulo: Work Orders

---

# Objetivo

Gestionar órdenes de trabajo técnicas para la ejecución de reparaciones, mantenimientos, instalaciones, diagnósticos y actividades operativas dentro de IAtechs Pro.

---

# Nombre Técnico

WorkOrders

---

# Tabla Principal

work_orders

---

# Dependencias

* Companies
* Branches
* Customers
* Devices
* Tickets
* Diagnostics
* Repairs
* ServiceContracts
* Users

---

# Descripción

Una Orden de Trabajo (Work Order) representa una tarea operativa asignada a uno o varios técnicos para ejecutar una actividad específica.

Puede originarse desde:

* Ticket
* Diagnóstico
* Reparación
* Contrato de servicio
* Mantenimiento programado
* Solicitud interna

---

# Estados

## Draft

```text id="l7y4m2"
draft
```

---

## Assigned

```text id="v4i0zq"
assigned
```

---

## InProgress

```text id="m0f38n"
in_progress
```

---

## OnHold

```text id="c6nkx4"
on_hold
```

---

## Completed

```text id="8ydbj3"
completed
```

---

## Cancelled

```text id="z7s0h2"
cancelled
```

---

# Prioridades

## Low

```text id="n9l1kp"
low
```

---

## Medium

```text id="s4m8tw"
medium
```

---

## High

```text id="r5v6zc"
high
```

---

## Critical

```text id="u1q8fd"
critical
```

---

# Tabla work_orders

| Campo               | Tipo      |
| ------------------- | --------- |
| id                  | bigint    |
| company_id          | bigint    |
| branch_id           | bigint    |
| customer_id         | bigint    |
| device_id           | bigint    |
| ticket_id           | bigint    |
| diagnostic_id       | bigint    |
| repair_id           | bigint    |
| service_contract_id | bigint    |
| work_order_number   | string    |
| title               | string    |
| description         | text      |
| priority            | string    |
| status              | string    |
| scheduled_start     | datetime  |
| scheduled_end       | datetime  |
| started_at          | datetime  |
| completed_at        | datetime  |
| assigned_to         | bigint    |
| created_by          | bigint    |
| created_at          | timestamp |
| updated_at          | timestamp |

---

# Tabla work_order_tasks

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| work_order_id | bigint    |
| task_name     | string    |
| description   | text      |
| status        | string    |
| completed_at  | timestamp |

---

# Migración Oficial Work Orders

```php
Schema::create('work_orders', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('customer_id')
        ->nullable()
        ->constrained('customers');

    $table->foreignId('device_id')
        ->nullable()
        ->constrained('devices');

    $table->foreignId('ticket_id')
        ->nullable()
        ->constrained('tickets');

    $table->foreignId('diagnostic_id')
        ->nullable()
        ->constrained('diagnostics');

    $table->foreignId('repair_id')
        ->nullable()
        ->constrained('repairs');

    $table->foreignId('service_contract_id')
        ->nullable()
        ->constrained('service_contracts');

    $table->string('work_order_number')
        ->unique();

    $table->string('title');

    $table->text('description')
        ->nullable();

    $table->enum('priority', [
        'low',
        'medium',
        'high',
        'critical'
    ])->default('medium');

    $table->enum('status', [
        'draft',
        'assigned',
        'in_progress',
        'on_hold',
        'completed',
        'cancelled'
    ])->default('draft');

    $table->timestamp('scheduled_start')
        ->nullable();

    $table->timestamp('scheduled_end')
        ->nullable();

    $table->timestamp('started_at')
        ->nullable();

    $table->timestamp('completed_at')
        ->nullable();

    $table->foreignId('assigned_to')
        ->nullable()
        ->constrained('users');

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Ticket

```php
public function ticket()
{
    return $this->belongsTo(Ticket::class);
}
```

---

## Diagnostic

```php
public function diagnostic()
{
    return $this->belongsTo(Diagnostic::class);
}
```

---

## Repair

```php
public function repair()
{
    return $this->belongsTo(Repair::class);
}
```

---

## Assigned Technician

```php
public function technician()
{
    return $this->belongsTo(User::class, 'assigned_to');
}
```

---

## Tasks

```php
public function tasks()
{
    return $this->hasMany(WorkOrderTask::class);
}
```

---

# Modelo

```text
app/Models/WorkOrder.php
```

---

# Repository

```text
app/Repositories/WorkOrderRepository.php
```

---

# Service

```text
app/Services/WorkOrderService.php
```

---

# Responsabilidades

* Crear órdenes de trabajo.
* Asignar técnicos.
* Gestionar tareas.
* Controlar tiempos.
* Monitorear SLA.
* Registrar avances.
* Cerrar trabajos.

---

# Policy

```text
WorkOrderPolicy
```

---

# Permisos

```text
work_orders.view
work_orders.create
work_orders.update
work_orders.delete
work_orders.assign
work_orders.complete
work_orders.export
```

---

# Endpoints Web

```http
GET     /work-orders
GET     /work-orders/create
POST    /work-orders
GET     /work-orders/{id}
PUT     /work-orders/{id}
DELETE  /work-orders/{id}

POST    /work-orders/{id}/assign
POST    /work-orders/{id}/complete
```

---

# Endpoints API

```http
GET     /api/v1/work-orders
POST    /api/v1/work-orders
GET     /api/v1/work-orders/{id}
PUT     /api/v1/work-orders/{id}
DELETE  /api/v1/work-orders/{id}
```

---

# Flujo de Negocio

## Orden Correctiva

```text
Ticket
   ↓
Diagnóstico
   ↓
Work Order
   ↓
Asignar Técnico
   ↓
Ejecución
   ↓
Completada
```

---

## Mantenimiento Programado

```text
Contrato
    ↓
Calendario
    ↓
Work Order
    ↓
Técnico
    ↓
Servicio
```

---

# Reglas de Negocio

## Regla 1

Toda orden debe pertenecer a una empresa.

---

## Regla 2

Toda orden asignada debe tener técnico responsable.

---

## Regla 3

Una orden completada no podrá editarse.

---

## Regla 4

El sistema deberá registrar tiempos reales de ejecución.

---

## Regla 5

Las órdenes críticas tendrán prioridad máxima.

---

## Regla 6

Todo cambio de estado quedará auditado.

---

# Auditoría

Registrar:

```text
Orden creada
Orden asignada
Inicio de trabajo
Pausa de trabajo
Trabajo completado
Trabajo cancelado
```

---

# Eventos

```text
WorkOrderCreated
WorkOrderAssigned
WorkOrderStarted
WorkOrderCompleted
WorkOrderCancelled
```

---

# Jobs

```text
NotifyTechnicianAssignmentJob
WorkOrderReminderJob
SlaMonitoringJob
GenerateWorkOrderReportJob
```

---

# Testing

## Unit Tests

```text
WorkOrderServiceTest
WorkOrderAssignmentTest
WorkOrderSlaTest
```

---

## Feature Tests

```text
CreateWorkOrderTest
AssignTechnicianTest
CompleteWorkOrderTest
WorkOrderWorkflowTest
```

---

# KPI del Módulo

* Órdenes completadas.
* Tiempo promedio de ejecución.
* Cumplimiento SLA.
* Productividad por técnico.
* Órdenes pendientes.
* Órdenes críticas.
* Tasa de retrabajo.

---

# Integración con Otros Módulos

```text
Tickets
Diagnostics
Repairs
ServiceContracts
TechnicianSchedules
Inventory
Notifications
Analytics
AuditLogs
```

---

# Resultado Esperado

El módulo Work Orders permitirá que IAtechs Pro gestione profesionalmente toda la operación técnica de la empresa, centralizando asignaciones, ejecución de trabajos, seguimiento de SLA, productividad de técnicos y trazabilidad completa de los servicios realizados.
