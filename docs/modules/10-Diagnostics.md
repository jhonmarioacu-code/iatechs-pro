# Module Specification

# IAtechs Pro

## Módulo: Diagnostics

---

# Objetivo

Gestionar los diagnósticos técnicos realizados sobre los equipos registrados en IAtechs Pro.

El diagnóstico permitirá identificar fallas, registrar observaciones técnicas, estimar costos y generar la base para una cotización y posterior reparación.

---

# Nombre Técnico

Diagnostics

---

# Tabla Principal

diagnostics

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Customers
* Devices
* Tickets
* Users

---

# Descripción

Un diagnóstico es la evaluación técnica realizada por un técnico sobre un equipo asociado a un ticket.

Puede existir más de un diagnóstico por ticket si se requiere una reevaluación.

---

# Estados del Diagnóstico

## Draft

Diagnóstico en elaboración.

```text id="v0u8sa"
draft
```

---

## Completed

Diagnóstico finalizado.

```text id="z5h2wn"
completed
```

---

## Approved

Aprobado para cotización o reparación.

```text id="m9q4rb"
approved
```

---

## Rejected

Diagnóstico rechazado.

```text id="a7k1pd"
rejected
```

---

# Severidad de Falla

## Low

```text id="w4t7nz"
low
```

---

## Medium

```text id="x2j8cv"
medium
```

---

## High

```text id="q6r5pm"
high
```

---

## Critical

```text id="b9u2ke"
critical
```

---

# Tabla diagnostics

| Campo           | Tipo      | Descripción |
| --------------- | --------- | ----------- |
| id              | bigint    |             |
| company_id      | bigint    |             |
| branch_id       | bigint    |             |
| ticket_id       | bigint    |             |
| device_id       | bigint    |             |
| technician_id   | bigint    |             |
| diagnosis_code  | string    |             |
| findings        | text      |             |
| root_cause      | text      |             |
| recommendations | text      |             |
| estimated_cost  | decimal   |             |
| estimated_hours | decimal   |             |
| severity        | string    |             |
| status          | string    |             |
| diagnosed_at    | timestamp |             |
| created_at      | timestamp |             |
| updated_at      | timestamp |             |

---

# Migración Oficial

```php
Schema::create('diagnostics', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches')
        ->nullOnDelete();

    $table->foreignId('ticket_id')
        ->constrained('tickets')
        ->cascadeOnDelete();

    $table->foreignId('device_id')
        ->constrained('devices')
        ->cascadeOnDelete();

    $table->foreignId('technician_id')
        ->constrained('users');

    $table->string('diagnosis_code')->unique();

    $table->longText('findings');

    $table->longText('root_cause')->nullable();

    $table->longText('recommendations')->nullable();

    $table->decimal('estimated_cost', 12, 2)
        ->default(0);

    $table->decimal('estimated_hours', 8, 2)
        ->default(0);

    $table->enum('severity', [
        'low',
        'medium',
        'high',
        'critical'
    ])->default('medium');

    $table->enum('status', [
        'draft',
        'completed',
        'approved',
        'rejected'
    ])->default('draft');

    $table->timestamp('diagnosed_at')->nullable();

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
    return $this->belongsTo(User::class, 'technician_id');
}
```

---

## Quote

```php
public function quote()
{
    return $this->hasOne(Quote::class);
}
```

---

## Repair

```php
public function repair()
{
    return $this->hasOne(Repair::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Diagnostic.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'branch_id',
    'ticket_id',
    'device_id',
    'technician_id',
    'diagnosis_code',
    'findings',
    'root_cause',
    'recommendations',
    'estimated_cost',
    'estimated_hours',
    'severity',
    'status',
    'diagnosed_at'
];
```

---

# Repository

Ubicación

```text
app/Repositories/DiagnosticRepository.php
```

---

# Service

Ubicación

```text
app/Services/DiagnosticService.php
```

---

# Responsabilidades

* Crear diagnósticos.
* Actualizar diagnósticos.
* Validar información técnica.
* Generar estimaciones.
* Aprobar diagnósticos.
* Generar datos para cotización.

---

# Request

## StoreDiagnosticRequest

Ubicación

```text
app/Http/Requests/Diagnostic
```

---

# Validaciones

```php
return [

    'ticket_id' => [
        'required',
        'exists:tickets,id'
    ],

    'device_id' => [
        'required',
        'exists:devices,id'
    ],

    'findings' => [
        'required'
    ]

];
```

---

# Policy

```text
DiagnosticPolicy
```

---

# Permisos

```text
diagnostics.view
diagnostics.create
diagnostics.update
diagnostics.delete
diagnostics.approve
diagnostics.export
```

---

# Endpoints Web

```http
GET     /diagnostics
GET     /diagnostics/create
POST    /diagnostics
GET     /diagnostics/{id}
GET     /diagnostics/{id}/edit
PUT     /diagnostics/{id}
DELETE  /diagnostics/{id}
```

---

# Endpoints API

```http
GET     /api/v1/diagnostics
POST    /api/v1/diagnostics
GET     /api/v1/diagnostics/{id}
PUT     /api/v1/diagnostics/{id}
DELETE  /api/v1/diagnostics/{id}

POST    /api/v1/diagnostics/{id}/approve
```

---

# Casos de Uso

## Crear Diagnóstico

```text
Técnico
      ↓
Inspección Equipo
      ↓
Registrar Hallazgos
      ↓
Guardar Diagnóstico
```

---

## Aprobar Diagnóstico

```text
Jefe Técnico
      ↓
Revisar Diagnóstico
      ↓
Aprobar
      ↓
Generar Cotización
```

---

## Solicitar Reevaluación

```text
Administrador
      ↓
Revisar Diagnóstico
      ↓
Rechazar
      ↓
Nueva Evaluación
```

---

# Reglas de Negocio

## Regla 1

Todo diagnóstico debe pertenecer a un ticket.

---

## Regla 2

Todo diagnóstico debe estar asociado a un equipo.

---

## Regla 3

Solo técnicos autorizados podrán generar diagnósticos.

---

## Regla 4

No se podrá crear una cotización sin un diagnóstico completado.

---

## Regla 5

Los diagnósticos aprobados no podrán modificarse.

---

## Regla 6

Todo cambio deberá registrarse en auditoría.

---

# Auditoría

Registrar:

```text
Diagnóstico creado
Diagnóstico actualizado
Diagnóstico aprobado
Diagnóstico rechazado
Cambio de estimación
```

---

# Eventos

```text
DiagnosticCreated
DiagnosticUpdated
DiagnosticApproved
DiagnosticRejected
```

---

# Jobs

```text
NotifyDiagnosticCompletedJob
NotifyDiagnosticApprovedJob
```

---

# Testing

## Unit Tests

```text
DiagnosticServiceTest
DiagnosticRepositoryTest
```

---

## Feature Tests

```text
CreateDiagnosticTest
ApproveDiagnosticTest
UpdateDiagnosticTest
DiagnosticWorkflowTest
```

---

# KPI del Módulo

* Diagnósticos realizados.
* Diagnósticos aprobados.
* Diagnósticos rechazados.
* Tiempo promedio de diagnóstico.
* Costos estimados.
* Diagnósticos por técnico.

---

# Integración con Otros Módulos

```text
Tickets
Devices
Quotes
Repairs
Notifications
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Diagnostics permitirá a IAtechs Pro realizar evaluaciones técnicas estructuradas, documentadas y auditables, garantizando que todas las reparaciones y cotizaciones estén respaldadas por un diagnóstico profesional y trazable.
