# Module Specification

# IAtechs Pro

## Módulo: Suppliers

---

# Objetivo

Administrar los proveedores de bienes y servicios utilizados por IAtechs Pro para abastecer inventario, adquirir repuestos, herramientas y gestionar servicios tercerizados.

---

# Nombre Técnico

Suppliers

---

# Tabla Principal

suppliers

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Users

---

# Descripción

Un proveedor representa una empresa o persona que suministra productos o servicios a la organización.

Los proveedores estarán vinculados posteriormente con:

* Inventory
* Purchases
* Purchase Orders
* Accounts Payable

---

# Tipos de Proveedor

## Distributor

```text
distributor
```

Distribuidor autorizado.

---

## Manufacturer

```text
manufacturer
```

Fabricante.

---

## Wholesaler

```text
wholesaler
```

Mayorista.

---

## Retailer

```text
retailer
```

Minorista.

---

## Service Provider

```text
service_provider
```

Proveedor de servicios.

---

# Estados

## Active

```text
active
```

Proveedor activo.

---

## Inactive

```text
inactive
```

Proveedor inactivo.

---

## Blocked

```text
blocked
```

Proveedor bloqueado.

---

# Tabla suppliers

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| supplier_code | string    |
| supplier_type | string    |
| company_name  | string    |
| tax_id        | string    |
| contact_name  | string    |
| email         | string    |
| phone         | string    |
| mobile        | string    |
| website       | string    |
| country       | string    |
| state         | string    |
| city          | string    |
| address       | text      |
| payment_terms | integer   |
| credit_limit  | decimal   |
| notes         | text      |
| status        | string    |
| created_at    | timestamp |
| updated_at    | timestamp |

---

# Migración Oficial

```php
Schema::create('suppliers', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->string('supplier_code')->unique();

    $table->enum('supplier_type', [
        'distributor',
        'manufacturer',
        'wholesaler',
        'retailer',
        'service_provider'
    ]);

    $table->string('company_name');

    $table->string('tax_id')->nullable();

    $table->string('contact_name')->nullable();

    $table->string('email')->nullable();

    $table->string('phone')->nullable();

    $table->string('mobile')->nullable();

    $table->string('website')->nullable();

    $table->string('country')->nullable();

    $table->string('state')->nullable();

    $table->string('city')->nullable();

    $table->text('address')->nullable();

    $table->integer('payment_terms')
        ->default(0);

    $table->decimal('credit_limit', 12, 2)
        ->default(0);

    $table->text('notes')->nullable();

    $table->enum('status', [
        'active',
        'inactive',
        'blocked'
    ])->default('active');

    $table->timestamps();

    $table->softDeletes();
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

## Purchases

```php
public function purchases()
{
    return $this->hasMany(Purchase::class);
}
```

---

## Inventory Items

```php
public function inventoryItems()
{
    return $this->belongsToMany(
        InventoryItem::class
    );
}
```

---

# Modelo

Ubicación

```text
app/Models/Supplier.php
```

---

# Fillable

```php
protected $fillable = [

    'company_id',
    'supplier_code',
    'supplier_type',
    'company_name',
    'tax_id',
    'contact_name',
    'email',
    'phone',
    'mobile',
    'website',
    'country',
    'state',
    'city',
    'address',
    'payment_terms',
    'credit_limit',
    'notes',
    'status'

];
```

---

# Repository

Ubicación

```text
app/Repositories/SupplierRepository.php
```

---

# Service

Ubicación

```text
app/Services/SupplierService.php
```

---

# Responsabilidades

* Registrar proveedores.
* Actualizar información.
* Gestionar límites de crédito.
* Gestionar condiciones de pago.
* Asociar productos.
* Consultar historial de compras.
* Evaluar desempeño del proveedor.

---

# Policy

```text
SupplierPolicy
```

---

# Permisos

```text
suppliers.view
suppliers.create
suppliers.update
suppliers.delete
suppliers.export
suppliers.block
```

---

# Endpoints Web

```http
GET     /suppliers
GET     /suppliers/create
POST    /suppliers
GET     /suppliers/{id}
PUT     /suppliers/{id}
DELETE  /suppliers/{id}
```

---

# Endpoints API

```http
GET     /api/v1/suppliers
POST    /api/v1/suppliers
GET     /api/v1/suppliers/{id}
PUT     /api/v1/suppliers/{id}
DELETE  /api/v1/suppliers/{id}
```

---

# Casos de Uso

## Registrar Proveedor

```text
Administrador
      ↓
Registrar Datos
      ↓
Guardar
      ↓
Proveedor Activo
```

---

## Bloquear Proveedor

```text
Incumplimiento
      ↓
Bloquear
      ↓
No permitir nuevas compras
```

---

## Consultar Historial

```text
Proveedor
      ↓
Compras
      ↓
Facturas
      ↓
Pagos
```

---

# Reglas de Negocio

## Regla 1

Todo proveedor pertenece a una empresa.

---

## Regla 2

El código de proveedor debe ser único.

---

## Regla 3

No se podrán crear órdenes de compra a proveedores bloqueados.

---

## Regla 4

Los proveedores eliminados usarán Soft Delete.

---

## Regla 5

Todo cambio importante deberá auditarse.

---

# Auditoría

Registrar:

```text
Proveedor creado
Proveedor actualizado
Cambio de estado
Cambio de límite crédito
Proveedor bloqueado
```

---

# Eventos

```text
SupplierCreated
SupplierUpdated
SupplierBlocked
SupplierActivated
```

---

# Jobs

```text
SupplierPerformanceCalculationJob
SupplierNotificationJob
```

---

# Testing

## Unit Tests

```text
SupplierServiceTest
SupplierValidationTest
```

---

## Feature Tests

```text
CreateSupplierTest
UpdateSupplierTest
BlockSupplierTest
SupplierListTest
```

---

# KPI del Módulo

* Proveedores activos.
* Compras por proveedor.
* Tiempo promedio de entrega.
* Valor comprado.
* Proveedores bloqueados.
* Cumplimiento de entregas.
* Ranking de proveedores.

---

# Integración con Otros Módulos

```text
Inventory
Purchases
Purchase Orders
Accounts Payable
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Suppliers permitirá a IAtechs Pro administrar de manera profesional la red de proveedores, optimizar compras, controlar costos, mejorar abastecimiento y mantener trazabilidad completa de la cadena de suministro empresarial.
