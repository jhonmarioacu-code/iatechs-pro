# Module Specification

# IAtechs Pro

## Módulo: Warranties

---

# Objetivo

Administrar las garantías otorgadas por la empresa sobre reparaciones, repuestos, equipos y servicios realizados, garantizando trazabilidad y control de reclamaciones.

---

# Nombre Técnico

Warranties

---

# Tabla Principal

warranties

---

# Dependencias

* Companies
* Branches
* Customers
* Devices
* Repairs
* Invoices
* Users

---

# Descripción

Una garantía representa el compromiso de cobertura sobre un trabajo realizado o un producto entregado.

Las garantías podrán aplicarse a:

* Reparaciones
* Repuestos
* Equipos vendidos
* Servicios técnicos

---

# Estados

## Active

```text
active
```

Garantía vigente.

---

## Expired

```text
expired
```

Garantía vencida.

---

## Claimed

```text
claimed
```

Garantía utilizada.

---

## Cancelled

```text
cancelled
```

Garantía anulada.

---

# Tipos de Garantía

## Repair Warranty

```text
repair
```

Garantía por reparación.

---

## Parts Warranty

```text
parts
```

Garantía de repuestos.

---

## Product Warranty

```text
product
```

Garantía de producto.

---

## Service Warranty

```text
service
```

Garantía de servicio.

---

# Tabla warranties

| Campo                | Tipo      |
| -------------------- | --------- |
| id                   | bigint    |
| company_id           | bigint    |
| branch_id            | bigint    |
| customer_id          | bigint    |
| device_id            | bigint    |
| repair_id            | bigint    |
| invoice_id           | bigint    |
| warranty_number      | string    |
| warranty_type        | string    |
| start_date           | date      |
| end_date             | date      |
| coverage_description | text      |
| terms_conditions     | text      |
| status               | string    |
| created_by           | bigint    |
| created_at           | timestamp |
| updated_at           | timestamp |

---

# Tabla warranty_claims

| Campo        | Tipo      |
| ------------ | --------- |
| id           | bigint    |
| warranty_id  | bigint    |
| claim_number | string    |
| claim_date   | datetime  |
| description  | text      |
| resolution   | text      |
| status       | string    |
| resolved_by  | bigint    |
| resolved_at  | timestamp |

---

# Estados de Reclamación

## Open

```text
open
```

---

## InReview

```text
in_review
```

---

## Approved

```text
approved
```

---

## Rejected

```text
rejected
```

---

## Closed

```text
closed
```

---

# Migración Oficial Warranties

```php
Schema::create('warranties', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('customer_id')
        ->constrained('customers');

    $table->foreignId('device_id')
        ->nullable()
        ->constrained('devices');

    $table->foreignId('repair_id')
        ->nullable()
        ->constrained('repairs');

    $table->foreignId('invoice_id')
        ->nullable()
        ->constrained('invoices');

    $table->string('warranty_number')
        ->unique();

    $table->enum('warranty_type', [
        'repair',
        'parts',
        'product',
        'service'
    ]);

    $table->date('start_date');

    $table->date('end_date');

    $table->text('coverage_description');

    $table->text('terms_conditions')
        ->nullable();

    $table->enum('status', [
        'active',
        'expired',
        'claimed',
        'cancelled'
    ])->default('active');

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

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

## Repair

```php
public function repair()
{
    return $this->belongsTo(Repair::class);
}
```

---

## Invoice

```php
public function invoice()
{
    return $this->belongsTo(Invoice::class);
}
```

---

## Claims

```php
public function claims()
{
    return $this->hasMany(WarrantyClaim::class);
}
```

---

# Modelo

```text
app/Models/Warranty.php
```

---

# Repository

```text
app/Repositories/WarrantyRepository.php
```

---

# Service

```text
app/Services/WarrantyService.php
```

---

# Responsabilidades

* Crear garantías.
* Gestionar vigencia.
* Registrar reclamaciones.
* Aprobar o rechazar cobertura.
* Controlar vencimientos.
* Emitir certificados de garantía.

---

# Policy

```text
WarrantyPolicy
```

---

# Permisos

```text
warranties.view
warranties.create
warranties.update
warranties.delete
warranties.claim
warranties.approve
warranties.export
```

---

# Endpoints Web

```http
GET     /warranties
GET     /warranties/create
POST    /warranties
GET     /warranties/{id}
PUT     /warranties/{id}
DELETE  /warranties/{id}

POST    /warranties/{id}/claim
POST    /warranties/{id}/approve
POST    /warranties/{id}/reject
```

---

# Endpoints API

```http
GET     /api/v1/warranties
POST    /api/v1/warranties
GET     /api/v1/warranties/{id}
PUT     /api/v1/warranties/{id}
DELETE  /api/v1/warranties/{id}
```

---

# Flujo de Negocio

## Crear Garantía

```text
Reparación Finalizada
        ↓
Generar Garantía
        ↓
Activa
```

---

## Reclamación

```text
Cliente Reporta Problema
          ↓
Validar Cobertura
          ↓
Aprobar o Rechazar
```

---

## Vencimiento

```text
Fecha Fin
      ↓
Expired
```

---

# Reglas de Negocio

## Regla 1

Toda garantía debe tener fecha de inicio y fin.

---

## Regla 2

No se podrán registrar reclamaciones sobre garantías vencidas.

---

## Regla 3

Las reclamaciones aprobadas podrán generar una nueva reparación.

---

## Regla 4

Toda garantía debe pertenecer a una empresa.

---

## Regla 5

Toda reclamación debe quedar auditada.

---

## Regla 6

La numeración debe ser única por empresa.

---

# Auditoría

Registrar:

```text
Garantía creada
Garantía reclamada
Garantía aprobada
Garantía rechazada
Garantía vencida
```

---

# Eventos

```text
WarrantyCreated
WarrantyClaimed
WarrantyApproved
WarrantyRejected
WarrantyExpired
```

---

# Jobs

```text
ExpireWarrantyJob
WarrantyExpirationReminderJob
NotifyWarrantyClaimJob
```

---

# Testing

## Unit Tests

```text
WarrantyServiceTest
WarrantyValidationTest
WarrantyClaimTest
```

---

## Feature Tests

```text
CreateWarrantyTest
ClaimWarrantyTest
ApproveWarrantyTest
RejectWarrantyTest
```

---

# KPI del Módulo

* Garantías activas.
* Garantías vencidas.
* Reclamaciones aprobadas.
* Reclamaciones rechazadas.
* Costos por garantía.
* Índice de retrabajos.
* Satisfacción postventa.

---

# Integración con Otros Módulos

```text
Customers
Devices
Repairs
Invoices
Tickets
Analytics
AuditLogs
Notifications
```

---

# Resultado Esperado

El módulo Warranties permitirá a IAtechs Pro administrar profesionalmente las garantías de servicios y productos, mejorar la experiencia postventa, controlar reclamaciones y mantener trazabilidad completa sobre la cobertura ofrecida a los clientes.
