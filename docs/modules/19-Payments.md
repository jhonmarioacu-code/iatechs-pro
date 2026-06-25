# Module Specification

# IAtechs Pro

## Módulo: Payments

---

# Objetivo

Administrar todos los pagos recibidos por la empresa, garantizando trazabilidad financiera, conciliación con facturas y control de ingresos.

---

# Nombre Técnico

Payments

---

# Tabla Principal

payments

---

# Dependencias

* Companies
* Branches
* Customers
* Invoices
* Users

---

# Descripción

Un pago representa una transacción financiera realizada por un cliente para cancelar parcial o totalmente una factura.

Los pagos podrán aplicarse a una o varias facturas dependiendo de las reglas de negocio.

---

# Estados

## Pending

```text
pending
```

Pago pendiente de confirmación.

---

## Confirmed

```text
confirmed
```

Pago confirmado.

---

## Partial

```text
partial
```

Pago parcial.

---

## Refunded

```text
refunded
```

Pago devuelto.

---

## Cancelled

```text
cancelled
```

Pago anulado.

---

# Métodos de Pago

## Cash

```text
cash
```

Efectivo.

---

## Credit Card

```text
credit_card
```

Tarjeta de crédito.

---

## Debit Card

```text
debit_card
```

Tarjeta débito.

---

## Bank Transfer

```text
bank_transfer
```

Transferencia bancaria.

---

## Mobile Payment

```text
mobile_payment
```

Nequi, Daviplata, billeteras digitales.

---

## Check

```text
check
```

Cheque.

---

# Tabla payments

| Campo                 | Tipo      |
| --------------------- | --------- |
| id                    | bigint    |
| company_id            | bigint    |
| branch_id             | bigint    |
| customer_id           | bigint    |
| invoice_id            | bigint    |
| payment_number        | string    |
| payment_date          | datetime  |
| amount                | decimal   |
| payment_method        | string    |
| transaction_reference | string    |
| notes                 | text      |
| status                | string    |
| received_by           | bigint    |
| created_at            | timestamp |
| updated_at            | timestamp |

---

# Migración Oficial Payments

```php
Schema::create('payments', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('customer_id')
        ->constrained('customers');

    $table->foreignId('invoice_id')
        ->constrained('invoices');

    $table->string('payment_number')
        ->unique();

    $table->timestamp('payment_date');

    $table->decimal('amount',12,2);

    $table->enum('payment_method',[
        'cash',
        'credit_card',
        'debit_card',
        'bank_transfer',
        'mobile_payment',
        'check'
    ]);

    $table->string('transaction_reference')
        ->nullable();

    $table->text('notes')
        ->nullable();

    $table->enum('status',[
        'pending',
        'confirmed',
        'partial',
        'refunded',
        'cancelled'
    ])->default('confirmed');

    $table->foreignId('received_by')
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

## Invoice

```php
public function invoice()
{
    return $this->belongsTo(Invoice::class);
}
```

---

## User

```php
public function receivedBy()
{
    return $this->belongsTo(User::class, 'received_by');
}
```

---

# Modelo

Ubicación

```text
app/Models/Payment.php
```

---

# Repository

Ubicación

```text
app/Repositories/PaymentRepository.php
```

---

# Service

Ubicación

```text
app/Services/PaymentService.php
```

---

# Responsabilidades

* Registrar pagos.
* Aplicar pagos a facturas.
* Gestionar abonos.
* Gestionar devoluciones.
* Generar comprobantes.
* Conciliar ingresos.
* Actualizar saldos.

---

# Policy

```text
PaymentPolicy
```

---

# Permisos

```text
payments.view
payments.create
payments.update
payments.delete
payments.refund
payments.export
```

---

# Endpoints Web

```http
GET     /payments
GET     /payments/create
POST    /payments
GET     /payments/{id}
PUT     /payments/{id}
DELETE  /payments/{id}

POST    /payments/{id}/refund
```

---

# Endpoints API

```http
GET     /api/v1/payments
POST    /api/v1/payments
GET     /api/v1/payments/{id}
PUT     /api/v1/payments/{id}
DELETE  /api/v1/payments/{id}
```

---

# Flujo de Negocio

## Pago Completo

```text
Factura Emitida
       ↓
Cliente Paga
       ↓
Registrar Pago
       ↓
Factura Paid
```

---

## Pago Parcial

```text
Factura Emitida
       ↓
Abono
       ↓
Actualizar Balance
       ↓
Partially Paid
```

---

## Reembolso

```text
Pago Confirmado
       ↓
Solicitud Reembolso
       ↓
Refunded
```

---

# Reglas de Negocio

## Regla 1

No se podrá registrar un pago superior al saldo pendiente.

---

## Regla 2

Todo pago debe asociarse a una factura.

---

## Regla 3

Los pagos actualizarán automáticamente el estado de la factura.

---

## Regla 4

Los reembolsos deberán quedar auditados.

---

## Regla 5

Los pagos anulados no afectarán ingresos.

---

## Regla 6

Toda transacción deberá tener trazabilidad completa.

---

# Auditoría

Registrar:

```text
Pago registrado
Pago confirmado
Pago parcial
Pago reembolsado
Pago anulado
```

---

# Eventos

```text
PaymentCreated
PaymentConfirmed
PaymentRefunded
InvoicePaid
InvoicePartiallyPaid
```

---

# Jobs

```text
GeneratePaymentReceiptJob
SendPaymentConfirmationJob
UpdateInvoiceBalanceJob
```

---

# Testing

## Unit Tests

```text
PaymentServiceTest
PaymentValidationTest
InvoiceBalanceTest
```

---

## Feature Tests

```text
CreatePaymentTest
PartialPaymentTest
RefundPaymentTest
InvoicePaymentWorkflowTest
```

---

# KPI del Módulo

* Ingresos diarios.
* Ingresos mensuales.
* Pagos pendientes.
* Facturas cobradas.
* Facturas vencidas.
* Métodos de pago más utilizados.
* Tasa de recuperación de cartera.

---

# Integración con Otros Módulos

```text
Invoices
Customers
Notifications
Analytics
AuditLogs
Branches
```

---

# Resultado Esperado

El módulo Payments permitirá a IAtechs Pro gestionar profesionalmente todos los ingresos de la empresa, garantizando conciliación financiera, control de cartera, trazabilidad de transacciones y automatización de cobros.
