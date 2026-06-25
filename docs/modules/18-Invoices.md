# Module Specification

# IAtechs Pro

## Módulo: Invoices

---

# Objetivo

Gestionar la facturación de servicios, reparaciones, repuestos y productos vendidos por IAtechs Pro, garantizando trazabilidad financiera, control de pagos e integración con los módulos operativos.

---

# Nombre Técnico

Invoices

---

# Tabla Principal

invoices

---

# Dependencias

* Companies
* Branches
* Customers
* Tickets
* Quotes
* Repairs
* Inventory
* Users

---

# Descripción

Una factura representa un documento financiero emitido a un cliente por productos o servicios prestados.

Puede originarse desde:

* Reparaciones finalizadas
* Cotizaciones aprobadas
* Venta de productos
* Servicios técnicos
* Contratos de mantenimiento

---

# Estados

## Draft

```text
draft
```

Factura en preparación.

---

## Issued

```text
issued
```

Factura emitida.

---

## Partially Paid

```text
partially_paid
```

Pago parcial.

---

## Paid

```text
paid
```

Factura pagada.

---

## Overdue

```text
overdue
```

Factura vencida.

---

## Cancelled

```text
cancelled
```

Factura anulada.

---

# Tabla invoices

| Campo           | Tipo      |
| --------------- | --------- |
| id              | bigint    |
| company_id      | bigint    |
| branch_id       | bigint    |
| customer_id     | bigint    |
| repair_id       | bigint    |
| quote_id        | bigint    |
| invoice_number  | string    |
| invoice_date    | date      |
| due_date        | date      |
| subtotal        | decimal   |
| tax_amount      | decimal   |
| discount_amount | decimal   |
| total_amount    | decimal   |
| balance_due     | decimal   |
| notes           | text      |
| status          | string    |
| created_by      | bigint    |
| created_at      | timestamp |
| updated_at      | timestamp |

---

# Tabla invoice_items

| Campo        | Tipo    |
| ------------ | ------- |
| id           | bigint  |
| invoice_id   | bigint  |
| description  | string  |
| quantity     | decimal |
| unit_price   | decimal |
| tax_amount   | decimal |
| total_amount | decimal |

---

# Migración Oficial Invoices

```php
Schema::create('invoices', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('customer_id')
        ->constrained('customers');

    $table->foreignId('repair_id')
        ->nullable()
        ->constrained('repairs');

    $table->foreignId('quote_id')
        ->nullable()
        ->constrained('quotes');

    $table->string('invoice_number')
        ->unique();

    $table->date('invoice_date');

    $table->date('due_date');

    $table->decimal('subtotal',12,2)
        ->default(0);

    $table->decimal('tax_amount',12,2)
        ->default(0);

    $table->decimal('discount_amount',12,2)
        ->default(0);

    $table->decimal('total_amount',12,2)
        ->default(0);

    $table->decimal('balance_due',12,2)
        ->default(0);

    $table->text('notes')
        ->nullable();

    $table->enum('status',[
        'draft',
        'issued',
        'partially_paid',
        'paid',
        'overdue',
        'cancelled'
    ])->default('draft');

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

## Repair

```php
public function repair()
{
    return $this->belongsTo(Repair::class);
}
```

---

## Quote

```php
public function quote()
{
    return $this->belongsTo(Quote::class);
}
```

---

## Items

```php
public function items()
{
    return $this->hasMany(InvoiceItem::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Invoice.php
```

---

# Repository

Ubicación

```text
app/Repositories/InvoiceRepository.php
```

---

# Service

Ubicación

```text
app/Services/InvoiceService.php
```

---

# Responsabilidades

* Crear facturas.
* Calcular impuestos.
* Aplicar descuentos.
* Controlar saldos.
* Gestionar vencimientos.
* Generar PDF.
* Integrar pagos.

---

# Policy

```text
InvoicePolicy
```

---

# Permisos

```text
invoices.view
invoices.create
invoices.update
invoices.delete
invoices.send
invoices.cancel
invoices.export
```

---

# Endpoints Web

```http
GET     /invoices
GET     /invoices/create
POST    /invoices
GET     /invoices/{id}
PUT     /invoices/{id}
DELETE  /invoices/{id}

POST    /invoices/{id}/send
POST    /invoices/{id}/cancel
```

---

# Endpoints API

```http
GET     /api/v1/invoices
POST    /api/v1/invoices
GET     /api/v1/invoices/{id}
PUT     /api/v1/invoices/{id}
DELETE  /api/v1/invoices/{id}
```

---

# Flujo de Negocio

## Facturar Reparación

```text
Reparación Completada
        ↓
Generar Factura
        ↓
Emitir
        ↓
Cobro
```

---

## Factura Pagada

```text
Cliente Paga
      ↓
Registrar Pago
      ↓
Estado Paid
```

---

## Factura Vencida

```text
Fecha Vencimiento
      ↓
Sin Pago
      ↓
Overdue
```

---

# Reglas de Negocio

## Regla 1

No se puede facturar una reparación cancelada.

---

## Regla 2

Las facturas pagadas no podrán modificarse.

---

## Regla 3

Toda factura debe tener al menos un item.

---

## Regla 4

Los pagos actualizarán automáticamente el saldo pendiente.

---

## Regla 5

Toda factura debe pertenecer a una empresa.

---

## Regla 6

La numeración será única por empresa.

---

# Auditoría

Registrar:

```text
Factura creada
Factura emitida
Factura enviada
Factura pagada
Factura anulada
```

---

# Eventos

```text
InvoiceCreated
InvoiceIssued
InvoicePaid
InvoiceOverdue
InvoiceCancelled
```

---

# Jobs

```text
GenerateInvoicePdfJob
SendInvoiceEmailJob
OverdueInvoiceReminderJob
```

---

# Testing

## Unit Tests

```text
InvoiceServiceTest
InvoiceCalculationTest
InvoiceTaxTest
```

---

## Feature Tests

```text
CreateInvoiceTest
PayInvoiceTest
CancelInvoiceTest
InvoiceWorkflowTest
```

---

# KPI del Módulo

* Facturación total.
* Facturas emitidas.
* Facturas pagadas.
* Facturas vencidas.
* Ingresos por sucursal.
* Ingresos por técnico.
* Ticket promedio de venta.

---

# Integración con Otros Módulos

```text
Customers
Quotes
Repairs
Payments
Analytics
AuditLogs
Notifications
```

---

# Resultado Esperado

El módulo Invoices permitirá a IAtechs Pro gestionar la facturación empresarial de manera profesional, garantizando control financiero, trazabilidad de ingresos, integración con reparaciones y automatización del proceso de cobro.
