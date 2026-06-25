# Module Specification

# IAtechs Pro

## Módulo: Devices

---

# Objetivo

Administrar los dispositivos o equipos registrados por los clientes dentro de IAtechs Pro.

Cada dispositivo tendrá historial técnico completo, incluyendo tickets, diagnósticos, cotizaciones, reparaciones y garantías.

---

# Nombre Técnico

Devices

---

# Tabla Principal

devices

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Customers
* Users

---

# Descripción

Un dispositivo representa un equipo físico recibido para diagnóstico, mantenimiento o reparación.

---

# Categorías de Equipos

## Computadores

```text
desktop
laptop
all_in_one
```

---

## Dispositivos Móviles

```text
smartphone
tablet
smartwatch
```

---

## Impresión

```text
printer
scanner
plotter
```

---

## Redes

```text
router
switch
access_point
```

---

## Electrónica

```text
television
monitor
console
other
```

---

# Estados del Equipo

## Received

```text
received
```

---

## Diagnosing

```text
diagnosing
```

---

## WaitingApproval

```text
waiting_approval
```

---

## Repairing

```text
repairing
```

---

## Ready

```text
ready
```

---

## Delivered

```text
delivered
```

---

## Cancelled

```text
cancelled
```

---

# Tabla devices

| Campo               | Tipo               | Descripción |
| ------------------- | ------------------ | ----------- |
| id                  | bigint             |             |
| company_id          | bigint             |             |
| branch_id           | bigint             |             |
| customer_id         | bigint             |             |
| asset_code          | string             |             |
| category            | string             |             |
| brand               | string             |             |
| model               | string             |             |
| serial_number       | string             |             |
| color               | string             |             |
| operating_system    | string             |             |
| accessories         | text               |             |
| problem_description | text               |             |
| status              | string             |             |
| received_at         | timestamp          |             |
| delivered_at        | timestamp nullable |             |
| created_at          | timestamp          |             |
| updated_at          | timestamp          |             |

---

# Migración Oficial

```php
Schema::create('devices', function (Blueprint $table) {

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

    $table->string('asset_code')->unique();

    $table->string('category');

    $table->string('brand')->nullable();

    $table->string('model')->nullable();

    $table->string('serial_number')->nullable();

    $table->string('color')->nullable();

    $table->string('operating_system')->nullable();

    $table->text('accessories')->nullable();

    $table->text('problem_description')->nullable();

    $table->enum('status', [
        'received',
        'diagnosing',
        'waiting_approval',
        'repairing',
        'ready',
        'delivered',
        'cancelled'
    ])->default('received');

    $table->timestamp('received_at');

    $table->timestamp('delivered_at')->nullable();

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

## Tickets

```php
public function tickets()
{
    return $this->hasMany(Ticket::class);
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

## Repairs

```php
public function repairs()
{
    return $this->hasMany(Repair::class);
}
```

---

## Warranties

```php
public function warranties()
{
    return $this->hasMany(Warranty::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Device.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'branch_id',
    'customer_id',
    'asset_code',
    'category',
    'brand',
    'model',
    'serial_number',
    'color',
    'operating_system',
    'accessories',
    'problem_description',
    'status',
    'received_at',
    'delivered_at'
];
```

---

# Repository

Ubicación

```text
app/Repositories/DeviceRepository.php
```

---

# Service

Ubicación

```text
app/Services/DeviceService.php
```

---

# Responsabilidades

* Registrar equipos.
* Actualizar información.
* Consultar historial.
* Gestionar estados.
* Controlar entregas.
* Gestionar garantías.

---

# Request

## StoreDeviceRequest

Ubicación

```text
app/Http/Requests/Device
```

---

# Validaciones

```php
return [

    'customer_id' => [
        'required',
        'exists:customers,id'
    ],

    'category' => [
        'required'
    ],

    'asset_code' => [
        'required',
        'unique:devices,asset_code'
    ]

];
```

---

# Policy

```text
DevicePolicy
```

---

# Permisos

```text
devices.view
devices.create
devices.update
devices.delete
devices.receive
devices.deliver
devices.history
```

---

# Endpoints Web

```http
GET     /devices
GET     /devices/create
POST    /devices
GET     /devices/{id}
GET     /devices/{id}/edit
PUT     /devices/{id}
DELETE  /devices/{id}
```

---

# Endpoints API

```http
GET     /api/v1/devices
POST    /api/v1/devices
GET     /api/v1/devices/{id}
PUT     /api/v1/devices/{id}
DELETE  /api/v1/devices/{id}
```

---

# Casos de Uso

## Registrar Equipo

```text
Recepción
      ↓
Seleccionar Cliente
      ↓
Registrar Equipo
      ↓
Generar Código Interno
      ↓
Equipo Recibido
```

---

## Entregar Equipo

```text
Equipo Reparado
      ↓
Validar Pago
      ↓
Entrega
      ↓
Estado Delivered
```

---

## Consultar Historial

```text
Buscar Equipo
      ↓
Ver Tickets
      ↓
Ver Diagnósticos
      ↓
Ver Reparaciones
```

---

# Reglas de Negocio

## Regla 1

Todo equipo debe pertenecer a un cliente.

---

## Regla 2

Todo equipo debe pertenecer a una empresa.

---

## Regla 3

El historial técnico nunca podrá eliminarse.

---

## Regla 4

Un equipo entregado no puede volver a estado recibido.

---

## Regla 5

El código interno del equipo debe ser único por empresa.

---

## Regla 6

La eliminación física está prohibida.

Se utilizará Soft Delete.

---

# Auditoría

Registrar:

```text
Equipo registrado
Cambio de estado
Actualización
Entrega
Garantía
```

---

# Eventos

```text
DeviceCreated
DeviceReceived
DeviceDelivered
DeviceUpdated
```

---

# Testing

## Unit Tests

```text
DeviceServiceTest
DeviceRepositoryTest
```

---

## Feature Tests

```text
CreateDeviceTest
UpdateDeviceTest
DeliverDeviceTest
DeviceHistoryTest
```

---

# KPI del Módulo

* Equipos registrados.
* Equipos por categoría.
* Equipos en reparación.
* Equipos entregados.
* Tiempo promedio de reparación.
* Garantías activas.

---

# Integración con Otros Módulos

```text
Customers
Tickets
Diagnostics
Quotes
Repairs
Invoices
Payments
Warranties
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Devices permitirá a IAtechs Pro administrar el ciclo de vida completo de los equipos ingresados al sistema, manteniendo trazabilidad total desde la recepción hasta la entrega, incluyendo diagnósticos, reparaciones, garantías y control histórico empresarial.
