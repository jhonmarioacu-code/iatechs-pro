# Module Specification

# IAtechs Pro

## Módulo: Quotes

---

# Objetivo

Gestionar las cotizaciones generadas a partir de diagnósticos técnicos realizados sobre equipos registrados en IAtechs Pro.

Las cotizaciones permitirán presentar al cliente los costos estimados de reparación, repuestos, mano de obra e impuestos antes de iniciar cualquier trabajo.

---

# Nombre Técnico

Quotes

---

# Tabla Principal

quotes

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Customers
* Devices
* Tickets
* Diagnostics
* Users

---

# Descripción

Una cotización es una propuesta económica formal generada a partir de un diagnóstico aprobado.

El cliente podrá aprobar, rechazar o solicitar revisión de la cotización.

---

# Estados de la Cotización

## Draft

```text id="q1"
draft
```

Cotización en elaboración.

---

## Sent

```text id="q2"
sent
```

Enviada al cliente.

---

## Approved

```text id="q3"
approved
```

Aprobada por el cliente.

---

## Rejected

```text id="q4"
rejected
```

Rechazada por el cliente.

---

## Expired

```text id="q5"
expired
```

Vencida.

---

## Converted

```text id="q6"
converted
```

Convertida en reparación.

---

# Tabla quotes

| Campo           | Tipo      | Descripción |
| --------------- | --------- | ----------- |
| id              | bigint    |             |
| company_id      | bigint    |             |
| branch_id       | bigint    |             |
| ticket_id       | bigint    |             |
| diagnostic_id   | bigint    |             |
| customer_id     | bigint    |             |
| quote_number    | string    |             |
| subtotal        | decimal   |             |
| tax_amount      | decimal   |             |
| discount_amount | decimal   |             |
| total_amount    | decimal   |             |
| notes           | text      |             |
| valid_until     | date      |             |
| status          | string    |             |
| approved_at     | timestamp |             |
| created_by      | bigint    |             |
| created_at      | timestamp |             |
| updated_at      | timestamp |             |

---

# Tabla quote_items

| Campo       | Tipo    |
| ----------- | ------- |
| id          | bigint  |
| quote_id    | bigint  |
| description | string  |
| quantity    | decimal |
| unit_price  | decimal |
| total       | decimal |

---

# Migración Oficial Quotes

```php
Schema::create('quotes', function (Blueprint $table) {

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

    $table->foreignId('customer_id')
        ->constrained('customers');

    $table->string('quote_number')->unique();

    $table->decimal('subtotal', 12, 2);

    $table->decimal('tax_amount', 12, 2)
        ->default(0);

    $table->decimal('discount_amount', 12, 2)
        ->default(0);

    $table->decimal('total_amount', 12, 2);

    $table->text('notes')->nullable();

    $table->date('valid_until');

    $table->enum('status', [
        'draft',
        'sent',
        'approved',
        'rejected',
        'expired',
        'converted'
    ])->default('draft');

    $table->timestamp('approved_at')->nullable();

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Migración Oficial Quote Items

```php
Schema::create('quote_items', function (Blueprint $table) {

    $table->id();

    $table->foreignId('quote_id')
        ->constrained('quotes')
        ->cascadeOnDelete();

    $table->string('description');

    $table->decimal('quantity', 12, 2);

    $table->decimal('unit_price', 12, 2);

    $table->decimal('total', 12, 2);

    $table->timestamps();
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

## Customer

```php
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

---

## Items

```php
public function items()
{
    return $this->hasMany(QuoteItem::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Quote.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'branch_id',
    'ticket_id',
    'diagnostic_id',
    'customer_id',
    'quote_number',
    'subtotal',
    'tax_amount',
    'discount_amount',
    'total_amount',
    'notes',
    'valid_until',
    'status',
    'approved_at',
    'created_by'
];
```

---

# Repository

Ubicación

```text
app/Repositories/QuoteRepository.php
```

---

# Service

Ubicación

```text
app/Services/QuoteService.php
```

---

# Responsabilidades

* Crear cotizaciones.
* Calcular impuestos.
* Aplicar descuentos.
* Enviar cotizaciones.
* Aprobar/Rechazar.
* Convertir a reparación.

---

# Policy

```text
QuotePolicy
```

---

# Permisos

```text
quotes.view
quotes.create
quotes.update
quotes.delete
quotes.send
quotes.approve
quotes.reject
quotes.convert
quotes.export
```

---

# Endpoints Web

```http
GET     /quotes
GET     /quotes/create
POST    /quotes
GET     /quotes/{id}
PUT     /quotes/{id}
DELETE  /quotes/{id}

POST    /quotes/{id}/send
POST    /quotes/{id}/approve
POST    /quotes/{id}/reject
```

---

# Endpoints API

```http
GET     /api/v1/quotes
POST    /api/v1/quotes
GET     /api/v1/quotes/{id}
PUT     /api/v1/quotes/{id}
DELETE  /api/v1/quotes/{id}

POST    /api/v1/quotes/{id}/send
POST    /api/v1/quotes/{id}/approve
POST    /api/v1/quotes/{id}/reject
```

---

# Casos de Uso

## Generar Cotización

```text
Diagnóstico Aprobado
        ↓
Crear Cotización
        ↓
Agregar Conceptos
        ↓
Calcular Totales
        ↓
Guardar
```

---

## Enviar al Cliente

```text
Cotización Lista
        ↓
Enviar Email / WhatsApp
        ↓
Estado Sent
```

---

## Aprobar

```text
Cliente Aprueba
        ↓
Estado Approved
        ↓
Crear Reparación
```

---

## Rechazar

```text
Cliente Rechaza
        ↓
Estado Rejected
```

---

# Reglas de Negocio

## Regla 1

No puede existir una cotización sin diagnóstico aprobado.

---

## Regla 2

Una cotización aprobada no podrá modificarse.

---

## Regla 3

Toda cotización debe tener al menos un item.

---

## Regla 4

Las cotizaciones vencidas no podrán aprobarse.

---

## Regla 5

La aprobación del cliente deberá registrarse en auditoría.

---

## Regla 6

La conversión a reparación debe ser automática.

---

# Auditoría

Registrar:

```text
Cotización creada
Cotización enviada
Cotización aprobada
Cotización rechazada
Cotización convertida
```

---

# Eventos

```text
QuoteCreated
QuoteSent
QuoteApproved
QuoteRejected
QuoteConverted
```

---

# Jobs

```text
SendQuoteEmailJob
SendQuoteReminderJob
ConvertQuoteToRepairJob
```

---

# Testing

## Unit Tests

```text
QuoteServiceTest
QuoteCalculationTest
```

---

## Feature Tests

```text
CreateQuoteTest
ApproveQuoteTest
RejectQuoteTest
ConvertQuoteTest
```

---

# KPI del Módulo

* Cotizaciones generadas.
* Cotizaciones aprobadas.
* Cotizaciones rechazadas.
* Tasa de conversión.
* Ingresos proyectados.
* Tiempo promedio de aprobación.

---

# Integración con Otros Módulos

```text
Diagnostics
Tickets
Customers
Repairs
Invoices
Payments
Notifications
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Quotes permitirá a IAtechs Pro gestionar cotizaciones profesionales, auditables y automatizadas, garantizando que toda reparación tenga autorización económica previa y trazabilidad completa desde el diagnóstico hasta la facturación.
