# Module Specification

# IAtechs Pro

## Módulo: Technician Schedules

---

# Objetivo

Administrar la agenda operativa de los técnicos, incluyendo horarios laborales, disponibilidad, turnos, permisos, vacaciones y asignación de servicios.

---

# Nombre Técnico

TechnicianSchedules

---

# Tabla Principal

technician_schedules

---

# Dependencias

* Companies
* Branches
* Users
* WorkOrders
* ServiceContracts

---

# Descripción

Este módulo centraliza la planificación y disponibilidad de los técnicos para garantizar una asignación eficiente de órdenes de trabajo, visitas de mantenimiento y servicios de campo.

---

# Estados

## Available

```text id="s0tq5p"
available
```

Disponible.

---

## Assigned

```text id="1hcds2"
assigned
```

Asignado a trabajos.

---

## Busy

```text id="wz2l8u"
busy
```

Ocupado.

---

## Vacation

```text id="h3h0j9"
vacation
```

Vacaciones.

---

## Leave

```text id="n7yu2d"
leave
```

Permiso o incapacidad.

---

# Tabla technician_schedules

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| technician_id | bigint    |
| work_date     | date      |
| start_time    | time      |
| end_time      | time      |
| status        | string    |
| notes         | text      |
| created_at    | timestamp |
| updated_at    | timestamp |

---

# Tabla technician_absences

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| technician_id | bigint    |
| absence_type  | string    |
| start_date    | date      |
| end_date      | date      |
| reason        | text      |
| approved_by   | bigint    |
| created_at    | timestamp |

---

# Tabla technician_routes

| Campo              | Tipo      |
| ------------------ | --------- |
| id                 | bigint    |
| technician_id      | bigint    |
| work_order_id      | bigint    |
| scheduled_datetime | datetime  |
| estimated_duration | integer   |
| created_at         | timestamp |

---

# Migración Oficial Technician Schedules

```php
Schema::create('technician_schedules', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('technician_id')
        ->constrained('users');

    $table->date('work_date');

    $table->time('start_time');

    $table->time('end_time');

    $table->enum('status', [
        'available',
        'assigned',
        'busy',
        'vacation',
        'leave'
    ])->default('available');

    $table->text('notes')
        ->nullable();

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Technician

```php
public function technician()
{
    return $this->belongsTo(User::class, 'technician_id');
}
```

---

## Routes

```php
public function routes()
{
    return $this->hasMany(TechnicianRoute::class);
}
```

---

# Modelo

```text
app/Models/TechnicianSchedule.php
```

---

# Repository

```text
app/Repositories/TechnicianScheduleRepository.php
```

---

# Service

```text
app/Services/TechnicianScheduleService.php
```

---

# Responsabilidades

* Gestionar horarios.
* Administrar disponibilidad.
* Programar rutas.
* Gestionar vacaciones.
* Gestionar permisos.
* Optimizar asignaciones.
* Monitorear carga laboral.

---

# Policy

```text
TechnicianSchedulePolicy
```

---

# Permisos

```text
technician_schedules.view
technician_schedules.create
technician_schedules.update
technician_schedules.delete
technician_schedules.assign
technician_schedules.export
```

---

# Endpoints Web

```http
GET     /technician-schedules
GET     /technician-schedules/create
POST    /technician-schedules
GET     /technician-schedules/{id}
PUT     /technician-schedules/{id}
DELETE  /technician-schedules/{id}
```

---

# Endpoints API

```http
GET     /api/v1/technician-schedules
POST    /api/v1/technician-schedules
GET     /api/v1/technician-schedules/{id}
PUT     /api/v1/technician-schedules/{id}
DELETE  /api/v1/technician-schedules/{id}
```

---

# Flujo de Negocio

## Asignación de Trabajo

```text
Work Order
      ↓
Buscar Técnico Disponible
      ↓
Asignar Agenda
      ↓
Actualizar Disponibilidad
```

---

## Vacaciones

```text
Solicitud
     ↓
Aprobación
     ↓
Bloqueo Agenda
     ↓
Vacation
```

---

## Ruta de Servicio

```text
Work Orders
      ↓
Optimización
      ↓
Ruta Técnica
      ↓
Ejecución
```

---

# Reglas de Negocio

## Regla 1

Un técnico no podrá tener horarios superpuestos.

---

## Regla 2

No se podrán asignar órdenes durante vacaciones.

---

## Regla 3

Toda ausencia debe ser aprobada.

---

## Regla 4

La carga laboral deberá monitorearse automáticamente.

---

## Regla 5

Las rutas deben respetar horarios laborales.

---

## Regla 6

Toda modificación quedará auditada.

---

# Auditoría

Registrar:

```text
Horario creado
Horario modificado
Ausencia registrada
Vacaciones aprobadas
Ruta generada
Asignación realizada
```

---

# Eventos

```text
TechnicianScheduleCreated
TechnicianAssigned
TechnicianVacationApproved
TechnicianRouteGenerated
WorkOrderScheduled
```

---

# Jobs

```text
GenerateDailySchedulesJob
OptimizeTechnicianRoutesJob
AvailabilityValidationJob
TechnicianReminderJob
```

---

# Testing

## Unit Tests

```text
TechnicianScheduleServiceTest
AvailabilityValidationTest
RouteOptimizationTest
```

---

## Feature Tests

```text
CreateScheduleTest
AssignTechnicianTest
VacationApprovalTest
GenerateRouteTest
```

---

# KPI del Módulo

* Horas trabajadas.
* Disponibilidad promedio.
* Órdenes por técnico.
* Cumplimiento de agenda.
* Tiempo de desplazamiento.
* Productividad técnica.
* Cumplimiento SLA.

---

# Integración con Otros Módulos

```text
WorkOrders
ServiceContracts
Users
Notifications
Analytics
AuditLogs
Branches
```

---

# Resultado Esperado

El módulo Technician Schedules permitirá a IAtechs Pro planificar y controlar eficientemente la disponibilidad de los técnicos, optimizar la asignación de servicios, reducir tiempos de respuesta y mejorar el cumplimiento de SLA empresariales.
