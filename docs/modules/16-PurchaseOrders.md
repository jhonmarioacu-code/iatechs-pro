# Module Specification

# IAtechs Pro

## Módulo: Purchase Orders

---

# Objetivo

Gestionar las órdenes de compra emitidas a proveedores para la adquisición de productos, repuestos, herramientas y consumibles necesarios para la operación de IAtechs Pro.

---

# Nombre Técnico

PurchaseOrders

---

# Tabla Principal

purchase_orders

---

# Dependencias

* Companies
* Branches
* Suppliers
* Inventory
* Purchases
* Users

---

# Descripción

Una Orden de Compra (PO) es un documento formal enviado a un proveedor para solicitar productos o servicios.

Las órdenes podrán ser aprobadas, enviadas, parcialmente recibidas o completamente recibidas.

---

# Estados

## Draft

```text
draft
```

---

## PendingApproval

```text
pending_approval
```

---

## Approved

```text
approved
```

---

## Sent

```text
sent
```

---

## PartiallyReceived

```text
partially_received
```

---

## Received

```text
received
```

---

## Cancelled

```text
cancelled
```

---

# Tabla purchase_orders

| Campo                  | Tipo      |
| ---------------------- | --------- |
| id                     | bigint    |
| company_id             | bigint    |
| branch_id              | bigint    |
| supplier_id            | bigint    |
| po_number              | string    |
| order_date             | date      |
| expected_delivery_date | date      |
| subtotal               | decimal   |
| tax_amount             | decimal   |
| discount_amount        | decimal   |
| total_amount           | decimal   |
| notes                  | text      |
| status                 | string    |
| approved_by            | bigint    |
| approved_at            | timestamp |
| created_by             | bigint    |
| created_at             | timestamp |
| updated_at             | timestamp |

---

# Tabla purchase_order_items

| Campo             | Tipo    |
| ----------------- | ------- |
| id                | bigint  |
| purchase_order_id | bigint  |
| inventory_item_id | bigint  |
| description       | string  |
| quantity          | decimal |
| received_quantity | decimal |
| unit_cost         | decimal |
| total_cost        | decimal |

---

# Migración Oficial Purchase Orders

```php
Schema::create('purchase_orders', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('supplier_id')
        ->constrained('suppliers');

    $table->string('po_number')->unique();

    $table->date('order_date');

    $table->date('expected_delivery_date')
        ->nullable();

    $table->decimal('subtotal',12,2)->default(0);

    $table->decimal('tax_amount',12,2)->default(0);

    $table->decimal('discount_amount',12,2)->default(0);

    $table->decimal('total_amount',12,2)->default(0);

    $table->text('notes')->nullable();

    $table->enum('status',[
        'draft',
        'pending_approval',
        'approved',
        'sent',
        'partially_received',
        'received',
        'cancelled'
    ])->default('draft');

    $table->foreignId('approved_by')
        ->nullable()
        ->constrained('users');

    $table->timestamp('approved_at')
        ->nullable();

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Supplier

```php
public function supplier()
{
    return $this->belongsTo(Supplier::class);
}
```

---

## Items

```php
public function items()
{
    return $this->hasMany(PurchaseOrderItem::class);
}
```

---

## Company

```php
public function company()
{
    return $this->belongsTo(Company::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/PurchaseOrder.php
```

---

# Repository

Ubicación

```text
app/Repositories/PurchaseOrderRepository.php
```

---

# Service

Ubicación

```text
app/Services/PurchaseOrderService.php
```

---

# Responsabilidades

* Crear órdenes de compra.
* Gestionar aprobaciones.
* Enviar órdenes al proveedor.
* Controlar entregas.
* Gestionar recepción parcial.
* Generar compras automáticamente.
* Actualizar inventario.

---

# Policy

```text
PurchaseOrderPolicy
```

---

# Permisos

```text
purchase_orders.view
purchase_orders.create
purchase_orders.update
purchase_orders.delete
purchase_orders.approve
purchase_orders.send
purchase_orders.receive
purchase_orders.export
```

---

# Endpoints Web

```http
GET     /purchase-orders
GET     /purchase-orders/create
POST    /purchase-orders
GET     /purchase-orders/{id}
PUT     /purchase-orders/{id}
DELETE  /purchase-orders/{id}

POST    /purchase-orders/{id}/approve
POST    /purchase-orders/{id}/send
POST    /purchase-orders/{id}/receive
```

---

# Endpoints API

```http
GET     /api/v1/purchase-orders
POST    /api/v1/purchase-orders
GET     /api/v1/purchase-orders/{id}
PUT     /api/v1/purchase-orders/{id}
DELETE  /api/v1/purchase-orders/{id}
```

---

# Flujo de Negocio

## Crear Orden

```text
Solicitud Compra
        ↓
Crear PO
        ↓
Agregar Productos
        ↓
Guardar
```

---

## Aprobar

```text
Gerencia
      ↓
Aprobación
      ↓
Approved
```

---

## Enviar

```text
Proveedor
      ↓
Recibe Orden
      ↓
Sent
```

---

## Recepción

```text
Mercancía
      ↓
Verificación
      ↓
Actualizar Stock
      ↓
Received
```

---

# Reglas de Negocio

## Regla 1

Toda orden debe tener proveedor.

---

## Regla 2

No se podrá enviar una orden sin aprobación.

---

## Regla 3

Toda recepción actualizará inventario.

---

## Regla 4

Las órdenes canceladas no podrán reactivarse.

---

## Regla 5

Se permitirán recepciones parciales.

---

## Regla 6

Toda aprobación quedará auditada.

---

# Auditoría

Registrar:

```text
Orden creada
Orden aprobada
Orden enviada
Recepción parcial
Recepción completa
Cancelación
```

---

# Eventos

```text
PurchaseOrderCreated
PurchaseOrderApproved
PurchaseOrderSent
PurchaseOrderReceived
PurchaseOrderCancelled
```

---

# Jobs

```text
SendPurchaseOrderEmailJob
UpdateInventoryAfterReceiptJob
NotifyPurchaseOrderApprovalJob
```

---

# Testing

## Unit Tests

```text
PurchaseOrderServiceTest
PurchaseOrderCalculationTest
```

---

## Feature Tests

```text
CreatePurchaseOrderTest
ApprovePurchaseOrderTest
ReceivePurchaseOrderTest
CancelPurchaseOrderTest
```

---

# KPI del Módulo

* Órdenes creadas.
* Órdenes aprobadas.
* Órdenes pendientes.
* Tiempo promedio de entrega.
* Órdenes recibidas.
* Cumplimiento de proveedores.
* Costos de abastecimiento.

---

# Integración con Otros Módulos

```text
Suppliers
Inventory
Purchases
AccountsPayable
Analytics
AuditLogs
```

---

# Resultado Esperado

El módulo Purchase Orders permitirá a IAtechs Pro controlar formalmente todo el proceso de abastecimiento empresarial, desde la solicitud interna hasta la recepción de mercancía, garantizando trazabilidad, aprobación jerárquica y actualización automática de inventario.
