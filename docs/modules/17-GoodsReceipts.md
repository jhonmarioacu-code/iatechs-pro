# Module Specification

# IAtechs Pro

## Módulo: Goods Receipts

---

# Objetivo

Gestionar la recepción física de productos provenientes de proveedores, validando cantidades, costos, diferencias y actualizando automáticamente el inventario.

---

# Nombre Técnico

GoodsReceipts

---

# Tabla Principal

goods_receipts

---

# Dependencias

* Companies
* Branches
* Suppliers
* PurchaseOrders
* Purchases
* Inventory
* Users

---

# Descripción

Un Goods Receipt representa la entrada física de mercancía recibida desde un proveedor.

Permite validar:

* Cantidad recibida
* Cantidad faltante
* Productos dañados
* Diferencias de entrega
* Actualización de inventario

---

# Estados

## Pending

```text
pending
```

Recepción pendiente.

---

## Partial

```text
partial
```

Recepción parcial.

---

## Completed

```text
completed
```

Recepción completa.

---

## Rejected

```text
rejected
```

Recepción rechazada.

---

# Tabla goods_receipts

| Campo             | Tipo      |
| ----------------- | --------- |
| id                | bigint    |
| company_id        | bigint    |
| branch_id         | bigint    |
| supplier_id       | bigint    |
| purchase_order_id | bigint    |
| receipt_number    | string    |
| receipt_date      | date      |
| notes             | text      |
| status            | string    |
| received_by       | bigint    |
| created_at        | timestamp |
| updated_at        | timestamp |

---

# Tabla goods_receipt_items

| Campo                  | Tipo    |
| ---------------------- | ------- |
| id                     | bigint  |
| goods_receipt_id       | bigint  |
| purchase_order_item_id | bigint  |
| inventory_item_id      | bigint  |
| ordered_quantity       | decimal |
| received_quantity      | decimal |
| damaged_quantity       | decimal |
| accepted_quantity      | decimal |
| unit_cost              | decimal |
| total_cost             | decimal |

---

# Migración Oficial Goods Receipts

```php
Schema::create('goods_receipts', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('supplier_id')
        ->constrained('suppliers');

    $table->foreignId('purchase_order_id')
        ->constrained('purchase_orders');

    $table->string('receipt_number')
        ->unique();

    $table->date('receipt_date');

    $table->text('notes')
        ->nullable();

    $table->enum('status', [
        'pending',
        'partial',
        'completed',
        'rejected'
    ])->default('pending');

    $table->foreignId('received_by')
        ->constrained('users');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Purchase Order

```php
public function purchaseOrder()
{
    return $this->belongsTo(PurchaseOrder::class);
}
```

---

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
    return $this->hasMany(GoodsReceiptItem::class);
}
```

---

# Modelo

```text
app/Models/GoodsReceipt.php
```

---

# Repository

```text
app/Repositories/GoodsReceiptRepository.php
```

---

# Service

```text
app/Services/GoodsReceiptService.php
```

---

# Responsabilidades

* Registrar recepción.
* Validar cantidades.
* Detectar diferencias.
* Registrar daños.
* Actualizar stock.
* Generar movimientos de inventario.
* Cerrar órdenes de compra.

---

# Policy

```text
GoodsReceiptPolicy
```

---

# Permisos

```text
goods_receipts.view
goods_receipts.create
goods_receipts.update
goods_receipts.delete
goods_receipts.receive
goods_receipts.export
```

---

# Endpoints Web

```http
GET     /goods-receipts
GET     /goods-receipts/create
POST    /goods-receipts
GET     /goods-receipts/{id}
PUT     /goods-receipts/{id}
DELETE  /goods-receipts/{id}
```

---

# Endpoints API

```http
GET     /api/v1/goods-receipts
POST    /api/v1/goods-receipts
GET     /api/v1/goods-receipts/{id}
PUT     /api/v1/goods-receipts/{id}
DELETE  /api/v1/goods-receipts/{id}
```

---

# Flujo de Negocio

## Recepción Completa

```text
Proveedor Entrega
        ↓
Validar Productos
        ↓
Actualizar Inventario
        ↓
Completed
```

---

## Recepción Parcial

```text
Proveedor Entrega Parcial
        ↓
Registrar Cantidad
        ↓
Partial
        ↓
Esperar Restante
```

---

## Mercancía Dañada

```text
Recepción
      ↓
Detectar Daños
      ↓
Registrar Incidencia
      ↓
Notificar Proveedor
```

---

# Reglas de Negocio

## Regla 1

No se podrá recibir más cantidad que la ordenada.

---

## Regla 2

Toda recepción actualizará inventario automáticamente.

---

## Regla 3

Las diferencias deberán registrarse.

---

## Regla 4

Toda mercancía dañada quedará documentada.

---

## Regla 5

Una recepción completada no podrá modificarse.

---

## Regla 6

Toda recepción generará auditoría.

---

# Auditoría

Registrar:

```text
Recepción creada
Recepción parcial
Recepción completa
Mercancía dañada
Recepción rechazada
```

---

# Eventos

```text
GoodsReceiptCreated
GoodsReceiptCompleted
GoodsReceiptRejected
InventoryUpdated
```

---

# Jobs

```text
UpdateInventoryAfterGoodsReceiptJob
NotifyPurchaseManagerJob
GenerateSupplierIncidentJob
```

---

# Testing

## Unit Tests

```text
GoodsReceiptServiceTest
GoodsReceiptValidationTest
InventoryUpdateTest
```

---

## Feature Tests

```text
CreateGoodsReceiptTest
ReceiveProductsTest
PartialReceiptTest
RejectReceiptTest
```

---

# KPI del Módulo

* Recepciones completadas.
* Recepciones parciales.
* Diferencias detectadas.
* Productos dañados.
* Tiempo promedio de recepción.
* Cumplimiento de proveedores.

---

# Integración con Otros Módulos

```text
PurchaseOrders
Purchases
Suppliers
Inventory
AccountsPayable
AuditLogs
Analytics
```

---

# Resultado Esperado

El módulo Goods Receipts permitirá a IAtechs Pro controlar profesionalmente la recepción de mercancía, garantizar la exactitud del inventario, registrar incidencias con proveedores y mantener trazabilidad completa desde la orden de compra hasta la entrada física de productos.
