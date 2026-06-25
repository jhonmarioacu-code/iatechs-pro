# Module Specification

# IAtechs Pro

## Módulo: Customers

---

# Objetivo

Administrar los clientes de cada empresa dentro de IAtechs Pro.

Este módulo permitirá almacenar la información completa de clientes naturales y corporativos, así como su historial de servicios, equipos, tickets, reparaciones y facturación.

---

# Nombre Técnico

Customers

---

# Tabla Principal

customers

---

# Dependencias

Este módulo depende de:

* Companies
* Branches
* Users
* Roles & Permissions

---

# Descripción

Un cliente representa una persona natural o empresa que recibe servicios técnicos a través de IAtechs Pro.

Cada cliente pertenece a una empresa y puede estar asociado a una sucursal específica.

---

# Tipos de Cliente

## Individual

Persona natural.

```text
individual
```

---

## Business

Empresa o persona jurídica.

```text
business
```

---

# Estados del Cliente

## Active

Cliente activo.

```text
active
```

---

## Inactive

Cliente inactivo.

```text
inactive
```

---

## Blocked

Cliente bloqueado.

```text
blocked
```

---

# Tabla customers

| Campo         | Tipo            | Descripción |
| ------------- | --------------- | ----------- |
| id            | bigint          |             |
| company_id    | bigint          |             |
| branch_id     | bigint          |             |
| customer_type | string          |             |
| first_name    | string          |             |
| last_name     | string          |             |
| company_name  | string nullable |             |
| tax_id        | string nullable |             |
| email         | string          |             |
| phone         | string          |             |
| mobile        | string nullable |             |
| address       | text            |             |
| city          | string          |             |
| state         | string          |             |
| country       | string          |             |
| postal_code   | string          |             |
| notes         | text nullable   |             |
| status        | string          |             |
| created_at    | timestamp       |             |
| updated_at    | timestamp       |             |

---

# Migración Oficial

```php
Schema::create('customers', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches')
        ->nullOnDelete();

    $table->enum('customer_type', [
        'individual',
        'business'
    ])->default('individual');

    $table->string('first_name');

    $table->string('last_name')->nullable();

    $table->string('company_name')->nullable();

    $table->string('tax_id')->nullable();

    $table->string('email')->nullable();

    $table->string('phone');

    $table->string('mobile')->nullable();

    $table->text('address')->nullable();

    $table->string('city')->nullable();

    $table->string('state')->nullable();

    $table->string('country')->default('Colombia');

    $table->string('postal_code')->nullable();

    $table->text('notes')->nullable();

    $table->enum('status', [
        'active',
        'inactive',
        'blocked'
    ])->default('active');

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

## Devices

Un cliente puede registrar múltiples equipos.

```php
public function devices()
{
    return $this->hasMany(Device::class);
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

## Invoices

```php
public function invoices()
{
    return $this->hasMany(Invoice::class);
}
```

---

## Payments

```php
public function payments()
{
    return $this->hasMany(Payment::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Customer.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'branch_id',
    'customer_type',
    'first_name',
    'last_name',
    'company_name',
    'tax_id',
    'email',
    'phone',
    'mobile',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'notes',
    'status'
];
```

---

# Repository

Ubicación

```text
app/Repositories/CustomerRepository.php
```

---

# Service

Ubicación

```text
app/Services/CustomerService.php
```

---

# Responsabilidades

* Crear clientes.
* Actualizar clientes.
* Buscar clientes.
* Gestionar historial.
* Bloquear clientes.
* Reactivar clientes.

---

# Request

## StoreCustomerRequest

Ubicación

```text
app/Http/Requests/Customer
```

---

# Validaciones

```php
return [

    'company_id' => [
        'required',
        'exists:companies,id'
    ],

    'first_name' => [
        'required',
        'string',
        'max:255'
    ],

    'phone' => [
        'required',
        'string',
        'max:30'
    ]

];
```

---

# Policy

```text
CustomerPolicy
```

---

# Permisos

```text
customers.view
customers.create
customers.update
customers.delete
customers.export
customers.block
customers.restore
```

---

# Endpoints Web

```http
GET     /customers
GET     /customers/create
POST    /customers
GET     /customers/{id}
GET     /customers/{id}/edit
PUT     /customers/{id}
DELETE  /customers/{id}
```

---

# Endpoints API

```http
GET     /api/v1/customers
POST    /api/v1/customers
GET     /api/v1/customers/{id}
PUT     /api/v1/customers/{id}
DELETE  /api/v1/customers/{id}
```

---

# Casos de Uso

## Registrar Cliente

```text
Recepcionista
      ↓
Crear Cliente
      ↓
Guardar Información
      ↓
Cliente Disponible
```

---

## Actualizar Cliente

```text
Administrador
      ↓
Editar Información
      ↓
Guardar Cambios
```

---

## Bloquear Cliente

```text
Administrador
      ↓
Cambiar Estado
      ↓
Bloqueado
```

---

# Reglas de Negocio

## Regla 1

Todo cliente debe pertenecer a una empresa.

---

## Regla 2

Los usuarios solo podrán visualizar clientes de su empresa.

---

## Regla 3

Los clientes bloqueados no podrán generar nuevas órdenes de servicio.

---

## Regla 4

No se eliminarán clientes físicamente.

Se utilizará estado lógico.

---

## Regla 5

El historial del cliente debe mantenerse permanentemente.

---

# Auditoría

Registrar:

```text
Cliente creado
Cliente actualizado
Cliente bloqueado
Cliente reactivado
Exportación de datos
```

---

# Eventos

```text
CustomerCreated
CustomerUpdated
CustomerBlocked
CustomerRestored
```

---

# Testing

## Unit Tests

```text
CustomerServiceTest
CustomerRepositoryTest
```

---

## Feature Tests

```text
CreateCustomerTest
UpdateCustomerTest
BlockCustomerTest
CustomerSearchTest
```

---

# KPI del Módulo

* Total de clientes.
* Clientes activos.
* Clientes bloqueados.
* Clientes por sucursal.
* Clientes nuevos por mes.
* Clientes recurrentes.

---

# Integración con Otros Módulos

```text
Companies
Branches
Devices
Tickets
Diagnostics
Repairs
Invoices
Payments
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Customers permitirá a IAtechs Pro gestionar de forma centralizada toda la información de clientes, manteniendo historial completo de operaciones, trazabilidad empresarial y soporte para entornos multiempresa y multisucursal bajo estándares enterprise.
