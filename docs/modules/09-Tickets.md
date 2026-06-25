# Module Specification

# IAtechs Pro

## Módulo: Tickets

---

# Objetivo

Gestionar las órdenes de servicio y solicitudes técnicas dentro de IAtechs Pro.

El ticket representa el expediente principal de trabajo sobre un equipo y centraliza toda la información relacionada con diagnósticos, cotizaciones, reparaciones, pagos y entregas.

---

# Nombre Técnico

Tickets

---

# Tabla Principal

tickets

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Customers
* Devices
* Users
* Roles & Permissions

---

# Descripción

Un ticket es una orden de servicio creada cuando un cliente entrega un equipo para revisión, diagnóstico, mantenimiento o reparación.

Cada ticket tendrá un número único y un ciclo de vida controlado.

---

# Estados del Ticket

## Open

Ticket recién creado.

```text
open
```

---

## Assigned

Asignado a un técnico.

```text
assigned
```

---

## Diagnosing

En diagnóstico.

```text
diagnosing
```

---

## WaitingApproval

Esperando aprobación de cotización.

```text
waiting_approval
```

---

## Repairing

En reparación.

```text
repairing
```

---

## Ready

Listo para entrega.

```text
ready
```

---

## Delivered

Equipo entregado.

```text
delivered
```

---

## Closed

Ticket finalizado.

```text
closed
```

---

## Cancelled

Servicio cancelado.

```text
cancelled
```

---

# Prioridades

## Low

```text
low
```

---

## Medium

```text
medium
```

---

## High

```text
high
```

---

## Critical

```text
critical
```

---

# Tabla tickets

| Campo         | Tipo               | Descripción |
| ------------- | ------------------ | ----------- |
| id            | bigint             |             |
| company_id    | bigint             |             |
| branch_id     | bigint             |             |
| customer_id   | bigint             |             |
| device_id     | bigint             |             |
| assigned_to   | bigint nullable    |             |
| ticket_number | string             |             |
| title         | string             |             |
| description   | text               |             |
| priority      | string             |             |
| status        | string             |             |
| received_by   | bigint             |             |
| opened_at     | timestamp          |             |
| closed_at     | timestamp nullable |             |
| created_at    | timestamp          |             |
| updated_at    | timestamp          |             |

---

# Migración Oficial

```php
Schema::create('tickets', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches')
        ->nullOnDelete();

    $table->foreignId('customer_id')
        ->constrained('customers')
        ->cascadeOnDelete();

    $table->foreignId('device_id')
        ->constrained('devices')
        ->cascadeOnDelete();

    $table->foreignId('assigned_to')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->foreignId('received_by')
        ->constrained('users');

    $table->string('ticket_number')->unique();

    $table->string('title');

    $table->text('description')->nullable();

    $table->enum('priority', [
        'low',
        'medium',
        'high',
        'critical'
    ])->default('medium');

    $table->enum('status', [
        'open',
        'assigned',
        'diagnosing',
        'waiting_approval',
        'repairing',
        'ready',
        'delivered',
        'closed',
        'cancelled'
    ])->default('open');

    $table->timestamp('opened_at');

    $table->timestamp('closed_at')->nullable();

    $table->timestamps();

    $table->softDeletes();
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

## Branch

```php
public function branch()
{
    return $this->belongsTo(Branch::class);
}
```

---

## Customer

```php
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

---

## Device

```php
public function device()
{
    return $this->belongsTo(Device::class);
}
```

---

## Technician

```php
public function technician()
{
    return $this->belongsTo(User::class, 'assigned_to');
}
```

---

## Diagnostics

```php
public function diagnostics()
{
    return $this->hasMany(Diagnostic::class);
}
```

---

## Quotes

```php
public function quotes()
{
    return $this->hasMany(Quote::class);
}
```

---

## Repairs

```php
public function repairs()
{
    return $this->hasMany(Repair::class);
}
```

---

## Comments

```php
public function comments()
{
    return $this->hasMany(TicketComment::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Ticket.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'branch_id',
    'customer_id',
    'device_id',
    'assigned_to',
    'received_by',
    'ticket_number',
    'title',
    'description',
    'priority',
    'status',
    'opened_at',
    'closed_at'
];
```

---

# Repository

Ubicación

```text
app/Repositories/TicketRepository.php
```

---

# Service

Ubicación

```text
app/Services/TicketService.php
```

---

# Responsabilidades

* Crear tickets.
* Asignar técnicos.
* Cambiar estados.
* Registrar comentarios.
* Consultar historial.
* Cerrar tickets.

---

# Request

## StoreTicketRequest

Ubicación

```text
app/Http/Requests/Ticket
```

---

# Validaciones

```php
return [

    'customer_id' => [
        'required',
        'exists:customers,id'
    ],

    'device_id' => [
        'required',
        'exists:devices,id'
    ],

    'title' => [
        'required',
        'string',
        'max:255'
    ]

];
```

---

# Policy

```text
TicketPolicy
```

---

# Permisos

```text
tickets.view
tickets.create
tickets.update
tickets.delete
tickets.assign
tickets.close
tickets.comment
tickets.export
```

---

# Endpoints Web

```http
GET     /tickets
GET     /tickets/create
POST    /tickets
GET     /tickets/{id}
GET     /tickets/{id}/edit
PUT     /tickets/{id}
DELETE  /tickets/{id}
```

---

# Endpoints API

```http
GET     /api/v1/tickets
POST    /api/v1/tickets
GET     /api/v1/tickets/{id}
PUT     /api/v1/tickets/{id}
DELETE  /api/v1/tickets/{id}

POST    /api/v1/tickets/{id}/assign
POST    /api/v1/tickets/{id}/close
```

---

# Casos de Uso

## Crear Ticket

```text
Cliente entrega equipo
        ↓
Recepcionista registra ticket
        ↓
Sistema genera número
        ↓
Ticket Open
```

---

## Asignar Técnico

```text
Administrador
        ↓
Selecciona técnico
        ↓
Estado Assigned
```

---

## Cerrar Ticket

```text
Reparación completada
        ↓
Equipo entregado
        ↓
Ticket Closed
```

---

# Reglas de Negocio

## Regla 1

Todo ticket debe estar asociado a un cliente.

---

## Regla 2

Todo ticket debe estar asociado a un equipo.

---

## Regla 3

El número de ticket debe ser único por empresa.

---

## Regla 4

Un ticket cerrado no puede volver a estado abierto.

---

## Regla 5

Todo cambio de estado debe registrarse en auditoría.

---

## Regla 6

Los usuarios solo podrán acceder a tickets de su empresa.

---

# Auditoría

Registrar:

```text
Ticket creado
Cambio de estado
Asignación de técnico
Comentarios
Cierre
Cancelación
```

---

# Eventos

```text
TicketCreated
TicketAssigned
TicketStatusChanged
TicketClosed
TicketCancelled
```

---

# Jobs

```text
NotifyTicketAssignedJob
NotifyTicketClosedJob
TicketReminderJob
```

---

# Testing

## Unit Tests

```text
TicketServiceTest
TicketRepositoryTest
```

---

## Feature Tests

```text
CreateTicketTest
AssignTicketTest
CloseTicketTest
TicketHistoryTest
```

---

# KPI del Módulo

* Tickets abiertos.
* Tickets cerrados.
* Tiempo promedio de reparación.
* Tickets por técnico.
* Tickets por sucursal.
* Tickets por categoría de equipo.
* SLA cumplidos.

---

# Integración con Otros Módulos

```text
Customers
Devices
Diagnostics
Quotes
Repairs
Invoices
Payments
Notifications
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Tickets será el centro operativo de IAtechs Pro, permitiendo controlar completamente el ciclo de atención técnica desde la recepción del equipo hasta la entrega final, garantizando trazabilidad, auditoría y gestión empresarial escalable.
