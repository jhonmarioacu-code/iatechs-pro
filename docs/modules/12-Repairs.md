# Module Specification

# IAtechs Pro

## Módulo: Repairs

---

# Objetivo

Gestionar las reparaciones técnicas realizadas sobre equipos registrados en IAtechs Pro.

El módulo permitirá controlar la ejecución de trabajos, asignación de técnicos, consumo de repuestos, tiempos de trabajo, pruebas finales y cierre de reparación.

---

# Nombre Técnico

Repairs

---

# Tabla Principal

repairs

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Customers
* Devices
* Tickets
* Diagnostics
* Quotes
* Users

---

# Descripción

Una reparación representa la ejecución práctica de una cotización aprobada.

Toda reparación debe originarse a partir de una cotización aprobada.

---

# Estados de Reparación

## Pending

```text id="r1"
pending
```

Pendiente de iniciar.

---

## Assigned

```text id="r2"
assigned
```

Asignada a técnico.

---

## InProgress

```text id="r3"
in_progress
```

En ejecución.

---

## WaitingParts

```text id="r4"
waiting_parts
```

Esperando repuestos.

---

## QualityControl

```text id="r5"
quality_control
```

En pruebas finales.

---

## Completed

```text id="r6"
completed
```

Finalizada.

---

## Cancelled

```text id="r7"
cancelled
```

Cancelada.

---

# Tabla repairs

| Campo          | Tipo      | Descripción |
| -------------- | --------- | ----------- |
| id             | bigint    |             |
| company_id     | bigint    |             |
| branch_id      | bigint    |             |
| ticket_id      | bigint    |             |
| diagnostic_id  | bigint    |             |
| quote_id       | bigint    |             |
| device_id      | bigint    |             |
| technician_id  | bigint    |             |
| repair_number  | string    |             |
| work_performed | longText  |             |
| internal_notes | longText  |             |
| labor_cost     | decimal   |             |
| parts_cost     | decimal   |             |
| total_cost     | decimal   |             |
| status         | string    |             |
| started_at     | timestamp |             |
| completed_at   | timestamp |             |
| created_at     | timestamp |             |
| updated_at     | timestamp |             |

---

# Tabla repair_parts

Control de repuestos utilizados.

| Campo             | Tipo    |
| ----------------- | ------- |
| id                | bigint  |
| repair_id         | bigint  |
| inventory_item_id | bigint  |
| quantity          | decimal |
| unit_cost         | decimal |
| total_cost        | decimal |

---

# Tabla repair_logs

Historial de actividades.

| Campo       | Tipo      |
| ----------- | --------- |
| id          | bigint    |
| repair_id   | bigint    |
| user_id     | bigint    |
| description | text      |
| created_at  | timestamp |

---

# Migración Oficial Repairs

```php id="rp001"
Schema::create('repairs', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('ticket_id')
        ->constrained('tickets');

    $table->foreignId('diagnostic_id')
        ->constrained('diagnostics');

    $table->foreignId('quote_id')
        ->constrained('quotes');

    $table->foreignId('device_id')
        ->constrained('devices');

    $table->foreignId('technician_id')
        ->constrained('users');

    $table->string('repair_number')->unique();

    $table->longText('work_performed')->nullable();

    $table->longText('internal_notes')->nullable();

    $table->decimal('labor_cost', 12, 2)
        ->default(0);

    $table->decimal('parts_cost', 12, 2)
        ->default(0);

    $table->decimal('total_cost', 12, 2)
        ->default(0);

    $table->enum('status', [
        'pending',
        'assigned',
        'in_progress',
        'waiting_parts',
        'quality_control',
        'completed',
        'cancelled'
    ])->default('pending');

    $table->timestamp('started_at')->nullable();

    $table->timestamp('completed_at')->nullable();

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Ticket

```php id="rp002"
public function ticket()
{
    return $this->belongsTo(Ticket::class);
}
```

---

## Diagnostic

```php id="rp003"
public function diagnostic()
{
    return $this->belongsTo(Diagnostic::class);
}
```

---

## Quote

```php id="rp004"
public function quote()
{
    return $this->belongsTo(Quote::class);
}
```

---

## Device

```php id="rp005"
public function device()
{
    return $this->belongsTo(Device::class);
}
```

---

## Technician

```php id="rp006"
public function technician()
{
    return $this->belongsTo(User::class);
}
```

---

## Parts

```php id="rp007"
public function parts()
{
    return $this->hasMany(RepairPart::class);
}
```

---

## Logs

```php id="rp008"
public function logs()
{
    return $this->hasMany(RepairLog::class);
}
```

---

# Modelo

Ubicación

```text id="rp009"
app/Models/Repair.php
```

---

# Repository

Ubicación

```text id="rp010"
app/Repositories/RepairRepository.php
```

---

# Service

Ubicación

```text id="rp011"
app/Services/RepairService.php
```

---

# Responsabilidades

* Crear reparaciones.
* Asignar técnicos.
* Registrar actividades.
* Controlar consumo de repuestos.
* Registrar tiempos.
* Gestionar pruebas.
* Cerrar reparaciones.

---

# Policy

```text id="rp012"
RepairPolicy
```

---

# Permisos

```text id="rp013"
repairs.view
repairs.create
repairs.update
repairs.delete
repairs.assign
repairs.complete
repairs.export
```

---

# Endpoints Web

```http id="rp014"
GET     /repairs
GET     /repairs/create
POST    /repairs
GET     /repairs/{id}
PUT     /repairs/{id}
DELETE  /repairs/{id}

POST    /repairs/{id}/assign
POST    /repairs/{id}/complete
```

---

# Endpoints API

```http id="rp015"
GET     /api/v1/repairs
POST    /api/v1/repairs
GET     /api/v1/repairs/{id}
PUT     /api/v1/repairs/{id}
DELETE  /api/v1/repairs/{id}
```

---

# Casos de Uso

## Crear Reparación

```text id="rp016"
Cotización Aprobada
        ↓
Generar Reparación
        ↓
Asignar Técnico
```

---

## Iniciar Trabajo

```text id="rp017"
Técnico
      ↓
Iniciar Reparación
      ↓
Estado InProgress
```

---

## Registrar Repuestos

```text id="rp018"
Seleccionar Inventario
      ↓
Consumir Stock
      ↓
Actualizar Costos
```

---

## Control de Calidad

```text id="rp019"
Pruebas Finales
      ↓
Validar Funcionamiento
      ↓
QualityControl
```

---

## Finalizar

```text id="rp020"
Pruebas Exitosas
      ↓
Completed
      ↓
Listo para Facturación
```

---

# Reglas de Negocio

## Regla 1

Toda reparación debe tener una cotización aprobada.

---

## Regla 2

No se pueden consumir repuestos inexistentes en inventario.

---

## Regla 3

Toda actividad debe registrarse en Repair Logs.

---

## Regla 4

Solo técnicos autorizados pueden ejecutar reparaciones.

---

## Regla 5

Una reparación completada no podrá modificarse.

---

## Regla 6

El costo total se calculará automáticamente.

---

# Auditoría

Registrar:

```text id="rp021"
Reparación creada
Asignación técnico
Cambio estado
Consumo repuestos
Control calidad
Finalización
```

---

# Eventos

```text id="rp022"
RepairCreated
RepairAssigned
RepairStarted
RepairCompleted
RepairCancelled
```

---

# Jobs

```text id="rp023"
NotifyRepairAssignedJob
NotifyRepairCompletedJob
UpdateInventoryAfterRepairJob
```

---

# Testing

## Unit Tests

```text id="rp024"
RepairServiceTest
RepairCostCalculationTest
InventoryConsumptionTest
```

---

## Feature Tests

```text id="rp025"
CreateRepairTest
AssignRepairTest
CompleteRepairTest
RepairWorkflowTest
```

---

# KPI del Módulo

* Reparaciones activas.
* Reparaciones completadas.
* Tiempo promedio de reparación.
* Costos de mano de obra.
* Costos de repuestos.
* Productividad por técnico.
* Índice de retrabajos.

---

# Integración con Otros Módulos

```text id="rp026"
Tickets
Diagnostics
Quotes
Inventory
Invoices
Payments
Warranties
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Repairs permitirá a IAtechs Pro ejecutar y controlar completamente los procesos técnicos de reparación, garantizando trazabilidad, control de costos, consumo de inventario, productividad de técnicos y calidad del servicio bajo estándares enterprise.
