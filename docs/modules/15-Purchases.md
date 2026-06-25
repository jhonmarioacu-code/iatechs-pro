# Module Specification

# IAtechs Pro

## Módulo: Purchases

---

# Objetivo

Gestionar las compras realizadas a proveedores para abastecer inventario, herramientas, consumibles y activos necesarios para la operación de IAtechs Pro.

---

# Nombre Técnico

Purchases

---

# Tabla Principal

purchases

---

# Dependencias

* Companies
* Branches
* Suppliers
* Inventory
* Users

---

# Descripción

Una compra representa una transacción realizada con un proveedor.

Las compras podrán generar:

* Entradas de inventario
* Cuentas por pagar
* Historial de abastecimiento
* Actualización de costos

---

# Estados de Compra

## Draft

```text
draft
```

Compra en elaboración.

---

## Pending

```text
pending
```

Pendiente de aprobación.

---

## Approved

```text
approved
```

Aprobada.

---

## Ordered

```text
ordered
```

Orden enviada al proveedor.

---

## Partially Received

```text
partially_received
```

Recepción parcial.

---

## Received

```text
received
```

Recepción completa.

---

## Cancelled

```text
cancelled
```

Compra cancelada.

---

# Tabla purchases

| Campo           | Tipo      |
| --------------- | --------- |
| id              | bigint    |
| company_id      | bigint    |
| branch_id       | bigint    |
| supplier_id     | bigint    |
| purchase_number | string    |
| purchase_date   | date      |
| subtotal        | decimal   |
| tax_amount      | decimal   |
| discount_amount | decimal   |
| total_amount    | decimal   |
| notes           | text      |
| status          | string    |
| approved_by     | bigint    |
| approved_at     | timestamp |
| created_by      | bigint    |
| created_at      | timestamp |
| updated_at      | timestamp |

---

# Tabla purchase_items

| Campo             | Tipo    |
| ----------------- | ------- |
| id                | bigint  |
| purchase_id       | bigint  |
| inventory_item_id | bigint  |
| description       | string  |
| quantity          | decimal |
| unit_cost         | decimal |
| total_cost        | decimal |

---

# Migración Oficial Purchases

```php
Schema::create('purchases', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('supplier_id')
        ->constrained('suppliers');

    $table->string('purchase_number')
        ->unique();

    $table->date('purchase_date');

    $table->decimal('subtotal', 12, 2)
        ->default(0);

    $table->decimal('tax_amount', 12, 2)
        ->default(0);

    $table->decimal('discount_amount', 12, 2)
        ->default(0);

    $table->decimal('total_amount', 12, 2)
        ->default(0);

    $table->text('notes')
        ->nullable();

    $table->enum('status', [
        'draft',
        'pending',
        'approved',
        'ordered',
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

# Migración Purchase Items

```php
Schema::create('purchase_items', function (Blueprint $table) {

    $table->id();

    $table->foreignId('purchase_id')
        ->constrained('purchases')
        ->cascadeOnDelete();

    $table->foreignId('inventory_item_id')
        ->nullable()
        ->constrained('inventory_items');

    $table->string('description');

    $table->decimal('quantity', 12, 2);

    $table->decimal('unit_cost', 12, 2);

    $table->decimal('total_cost', 12, 2);

    $table->timestamps();
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
    return $this->hasMany(PurchaseItem::class);
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

```text
app/Models/Purchase.php
```

---

# Repository

```text
app/Repositories/PurchaseRepository.php
```

---

# Service

```text
app/Services/PurchaseService.php
```

---

# Responsabilidades

* Crear compras.
* Aprobar compras.
* Gestionar recepción.
* Actualizar costos.
* Generar movimientos de inventario.
* Integrar cuentas por pagar.

---

# Policy

```text
PurchasePolicy
```

---

# Permisos

```text
purchases.view
purchases.create
purchases.update
purchases.delete
purchases.approve
purchases.receive
purchases.export
```

---

# Endpoints Web

```http
GET     /purchases
GET     /purchases/create
POST    /purchases
GET     /purchases/{id}
PUT     /purchases/{id}
DELETE  /purchases/{id}

POST    /purchases/{id}/approve
POST    /purchases/{id}/receive
```

---

# Endpoints API

```http
GET     /api/v1/purchases
POST    /api/v1/purchases
GET     /api/v1/purchases/{id}
PUT     /api/v1/purchases/{id}
DELETE  /api/v1/purchases/{id}
```

---

# Flujo de Negocio

## Crear Compra

```text
Administrador
        ↓
Seleccionar Proveedor
        ↓
Agregar Productos
        ↓
Guardar
```

---

## Aprobar Compra

```text
Gerencia
      ↓
Revisar Compra
      ↓
Approved
```

---

## Recepción

```text
Proveedor Entrega
      ↓
Verificar Mercancía
      ↓
Actualizar Inventario
      ↓
Received
```

---

# Reglas de Negocio

## Regla 1

Toda compra debe tener proveedor.

---

## Regla 2

Una compra aprobada no podrá modificarse.

---

## Regla 3

La recepción generará movimientos de inventario.

---

## Regla 4

Las compras canceladas no afectarán inventario.

---

## Regla 5

Toda recepción debe quedar auditada.

---

## Regla 6

Los costos promedio de inventario podrán recalcularse automáticamente.

---

# Auditoría

Registrar:

```text
Compra creada
Compra aprobada
Compra cancelada
Recepción parcial
Recepción completa
```

---

# Eventos

```text
PurchaseCreated
PurchaseApproved
PurchaseReceived
PurchaseCancelled
```

---

# Jobs

```text
UpdateInventoryAfterPurchaseJob
CreateAccountsPayableJob
NotifyPurchaseApprovalJob
```

---

# Testing

## Unit Tests

```text
PurchaseServiceTest
PurchaseCalculationTest
```

---

## Feature Tests

```text
CreatePurchaseTest
ApprovePurchaseTest
ReceivePurchaseTest
CancelPurchaseTest
```

---

# KPI del Módulo

* Compras por proveedor.
* Valor total comprado.
* Tiempo promedio de entrega.
* Compras por sucursal.
* Costos de abastecimiento.
* Compras pendientes.
* Compras recibidas.

---

# Integración con Otros Módulos

```text
Suppliers
Inventory
AccountsPayable
Analytics
AuditLogs
Branches
```

---

# Resultado Esperado

El módulo Purchases permitirá a IAtechs Pro controlar profesionalmente todo el abastecimiento empresarial, garantizando trazabilidad desde la solicitud hasta la recepción de productos, actualización automática de inventario y control financiero completo.
