# Module Specification

# IAtechs Pro

## Módulo: Inventory

---

# Objetivo

Gestionar el inventario de repuestos, accesorios, componentes y materiales utilizados en las operaciones técnicas de IAtechs Pro.

El módulo permitirá controlar existencias, movimientos de stock, costos, transferencias entre sucursales y consumo automático durante las reparaciones.

---

# Nombre Técnico

Inventory

---

# Tablas Principales

```text
inventory_items
inventory_movements
inventory_categories
inventory_locations
```

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Users
* Repairs
* Purchases
* Suppliers

---

# Descripción

El inventario administrará todos los artículos físicos utilizados dentro de la empresa.

Cada sucursal podrá tener inventario independiente.

---

# Tipos de Productos

## Spare Part

```text
spare_part
```

Repuesto técnico.

---

## Accessory

```text
accessory
```

Accesorio.

---

## Tool

```text
tool
```

Herramienta.

---

## Consumable

```text
consumable
```

Material consumible.

---

# Estados de Inventario

## Active

```text
active
```

Disponible.

---

## Inactive

```text
inactive
```

Deshabilitado.

---

## Discontinued

```text
discontinued
```

Fuera de uso.

---

# Tabla inventory_categories

| Campo       | Tipo      |
| ----------- | --------- |
| id          | bigint    |
| company_id  | bigint    |
| name        | string    |
| description | text      |
| created_at  | timestamp |
| updated_at  | timestamp |

---

# Tabla inventory_items

| Campo          | Tipo      |
| -------------- | --------- |
| id             | bigint    |
| company_id     | bigint    |
| branch_id      | bigint    |
| category_id    | bigint    |
| sku            | string    |
| barcode        | string    |
| name           | string    |
| description    | text      |
| product_type   | string    |
| unit_cost      | decimal   |
| sale_price     | decimal   |
| stock_quantity | decimal   |
| minimum_stock  | decimal   |
| status         | string    |
| created_at     | timestamp |
| updated_at     | timestamp |

---

# Tabla inventory_movements

| Campo          | Tipo      |
| -------------- | --------- |
| id             | bigint    |
| item_id        | bigint    |
| movement_type  | string    |
| quantity       | decimal   |
| previous_stock | decimal   |
| new_stock      | decimal   |
| reference_type | string    |
| reference_id   | bigint    |
| notes          | text      |
| created_by     | bigint    |
| created_at     | timestamp |

---

# Tipos de Movimiento

## Stock In

```text
stock_in
```

Entrada de inventario.

---

## Stock Out

```text
stock_out
```

Salida de inventario.

---

## Transfer

```text
transfer
```

Transferencia.

---

## Adjustment

```text
adjustment
```

Ajuste manual.

---

## Repair Consumption

```text
repair_consumption
```

Consumo por reparación.

---

# Migración Oficial inventory_items

```php
Schema::create('inventory_items', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('category_id')
        ->nullable()
        ->constrained('inventory_categories');

    $table->string('sku')->unique();

    $table->string('barcode')->nullable();

    $table->string('name');

    $table->text('description')->nullable();

    $table->enum('product_type', [
        'spare_part',
        'accessory',
        'tool',
        'consumable'
    ]);

    $table->decimal('unit_cost', 12, 2)
        ->default(0);

    $table->decimal('sale_price', 12, 2)
        ->default(0);

    $table->decimal('stock_quantity', 12, 2)
        ->default(0);

    $table->decimal('minimum_stock', 12, 2)
        ->default(0);

    $table->enum('status', [
        'active',
        'inactive',
        'discontinued'
    ])->default('active');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Category

```php
public function category()
{
    return $this->belongsTo(InventoryCategory::class);
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

## Movements

```php
public function movements()
{
    return $this->hasMany(InventoryMovement::class);
}
```

---

## Repair Parts

```php
public function repairParts()
{
    return $this->hasMany(RepairPart::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/InventoryItem.php
```

---

# Repository

Ubicación

```text
app/Repositories/InventoryRepository.php
```

---

# Service

Ubicación

```text
app/Services/InventoryService.php
```

---

# Responsabilidades

* Crear productos.
* Actualizar stock.
* Registrar movimientos.
* Gestionar transferencias.
* Validar existencias.
* Generar alertas de stock mínimo.
* Controlar consumo por reparación.

---

# Policy

```text
InventoryPolicy
```

---

# Permisos

```text
inventory.view
inventory.create
inventory.update
inventory.delete
inventory.adjust
inventory.transfer
inventory.export
```

---

# Endpoints Web

```http
GET     /inventory
GET     /inventory/create
POST    /inventory
GET     /inventory/{id}
PUT     /inventory/{id}
DELETE  /inventory/{id}

POST    /inventory/adjust
POST    /inventory/transfer
```

---

# Endpoints API

```http
GET     /api/v1/inventory
POST    /api/v1/inventory
GET     /api/v1/inventory/{id}
PUT     /api/v1/inventory/{id}
DELETE  /api/v1/inventory/{id}
```

---

# Casos de Uso

## Registrar Repuesto

```text
Administrador
      ↓
Crear Producto
      ↓
Asignar Categoría
      ↓
Registrar Stock Inicial
```

---

## Consumir Inventario

```text
Reparación
      ↓
Seleccionar Repuesto
      ↓
Reducir Stock
      ↓
Registrar Movimiento
```

---

## Transferir Stock

```text
Sucursal A
      ↓
Transferencia
      ↓
Sucursal B
```

---

## Alerta de Stock

```text
Stock < Mínimo
      ↓
Generar Notificación
      ↓
Solicitud de Compra
```

---

# Reglas de Negocio

## Regla 1

No se permitirá stock negativo.

---

## Regla 2

Todo movimiento debe registrarse.

---

## Regla 3

Los consumos por reparación serán automáticos.

---

## Regla 4

Toda transferencia debe generar dos movimientos.

---

## Regla 5

Los productos descontinuados no podrán utilizarse en nuevas reparaciones.

---

## Regla 6

El SKU debe ser único por empresa.

---

# Auditoría

Registrar:

```text
Producto creado
Movimiento inventario
Ajuste stock
Transferencia
Consumo reparación
```

---

# Eventos

```text
InventoryCreated
StockAdjusted
StockTransferred
StockConsumed
LowStockDetected
```

---

# Jobs

```text
LowStockNotificationJob
GeneratePurchaseSuggestionJob
InventoryRecalculationJob
```

---

# Testing

## Unit Tests

```text
InventoryServiceTest
StockCalculationTest
TransferStockTest
```

---

## Feature Tests

```text
CreateInventoryItemTest
AdjustStockTest
TransferStockTest
ConsumeInventoryTest
```

---

# KPI del Módulo

* Valor total inventario.
* Stock disponible.
* Productos críticos.
* Consumo mensual.
* Rotación de inventario.
* Productos más utilizados.
* Productos sin movimiento.

---

# Integración con Otros Módulos

```text
Repairs
Purchases
Suppliers
Invoices
Analytics
Audit Logs
Branches
```

---

# Resultado Esperado

El módulo Inventory permitirá a IAtechs Pro administrar de forma profesional y centralizada todos los recursos físicos de la empresa, garantizando control de existencias, trazabilidad de movimientos, optimización de costos y abastecimiento eficiente para las operaciones técnicas.
