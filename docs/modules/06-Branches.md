# Module Specification

# IAtechs Pro

## Módulo: Branches

---

# Objetivo

Administrar las sucursales, sedes y puntos de operación de cada empresa dentro de IAtechs Pro.

Las sucursales permiten distribuir usuarios, clientes, inventario, tickets y operaciones en diferentes ubicaciones físicas.

---

# Nombre Técnico

Branches

---

# Tabla Principal

branches

---

# Dependencias

Este módulo depende de:

* Companies
* Users
* Roles & Permissions

---

# Descripción

Una empresa puede tener una o múltiples sucursales.

Cada sucursal funciona como una unidad operativa independiente, pero manteniendo la pertenencia a la misma empresa.

---

# Casos de Uso

* Taller principal.
* Sucursal secundaria.
* Centro de servicio.
* Punto de recepción.
* Centro logístico.
* Oficina administrativa.

---

# Estados de Sucursal

## Active

Sucursal operativa.

```text id="j4q7mr"
active
```

---

## Inactive

Sucursal deshabilitada.

```text id="n8v2kt"
inactive
```

---

# Tabla branches

| Campo       | Tipo            | Descripción |
| ----------- | --------------- | ----------- |
| id          | bigint          |             |
| company_id  | bigint          |             |
| name        | string          |             |
| code        | string          |             |
| email       | string          |             |
| phone       | string          |             |
| address     | text            |             |
| city        | string          |             |
| state       | string          |             |
| country     | string          |             |
| postal_code | string          |             |
| manager_id  | bigint nullable |             |
| status      | string          |             |
| created_at  | timestamp       |             |
| updated_at  | timestamp       |             |

---

# Migración Oficial

```php
Schema::create('branches', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->string('name');

    $table->string('code')->unique();

    $table->string('email')->nullable();

    $table->string('phone')->nullable();

    $table->text('address')->nullable();

    $table->string('city')->nullable();

    $table->string('state')->nullable();

    $table->string('country')->default('Colombia');

    $table->string('postal_code')->nullable();

    $table->foreignId('manager_id')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->enum('status', [
        'active',
        'inactive'
    ])->default('active');

    $table->timestamps();
});
```

---

# Relaciones

## Company

Una empresa posee múltiples sucursales.

```php
public function company()
{
    return $this->belongsTo(Company::class);
}
```

---

## Manager

Responsable de la sucursal.

```php
public function manager()
{
    return $this->belongsTo(User::class, 'manager_id');
}
```

---

## Users

Usuarios asignados a la sucursal.

```php
public function users()
{
    return $this->hasMany(User::class);
}
```

---

## Customers

Clientes asociados.

```php
public function customers()
{
    return $this->hasMany(Customer::class);
}
```

---

## Devices

Equipos registrados.

```php
public function devices()
{
    return $this->hasMany(Device::class);
}
```

---

## Tickets

Tickets gestionados.

```php
public function tickets()
{
    return $this->hasMany(Ticket::class);
}
```

---

## Inventory

Inventario por sucursal.

```php
public function inventories()
{
    return $this->hasMany(Inventory::class);
}
```

---

# Modelo

Ubicación

```text
app/Models/Branch.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'name',
    'code',
    'email',
    'phone',
    'address',
    'city',
    'state',
    'country',
    'postal_code',
    'manager_id',
    'status'
];
```

---

# Repository

Ubicación

```text
app/Repositories/BranchRepository.php
```

---

# Responsabilidades

* Crear sucursales.
* Actualizar sucursales.
* Buscar sucursales.
* Activar sucursales.
* Desactivar sucursales.

---

# Service

Ubicación

```text
app/Services/BranchService.php
```

---

# Responsabilidades

* Crear sucursal.
* Asignar gerente.
* Validar límites del plan.
* Gestionar sedes.
* Gestionar operaciones multi-sucursal.

---

# Request

## StoreBranchRequest

Ubicación

```text
app/Http/Requests/Branch
```

---

# Validaciones

```php
return [

    'company_id' => [
        'required',
        'exists:companies,id'
    ],

    'name' => [
        'required',
        'string',
        'max:255'
    ],

    'code' => [
        'required',
        'unique:branches,code'
    ]

];
```

---

# Policy

```text
BranchPolicy
```

---

# Permisos

```text
branches.view
branches.create
branches.update
branches.delete
branches.activate
branches.deactivate
```

---

# Endpoints Web

```http
GET     /branches
GET     /branches/create
POST    /branches
GET     /branches/{id}
GET     /branches/{id}/edit
PUT     /branches/{id}
DELETE  /branches/{id}
```

---

# Endpoints API

```http
GET     /api/v1/branches
POST    /api/v1/branches
GET     /api/v1/branches/{id}
PUT     /api/v1/branches/{id}
DELETE  /api/v1/branches/{id}
```

---

# Casos de Uso

## Crear Sucursal

```text
Company Owner
      ↓
Crear Sucursal
      ↓
Asignar Gerente
      ↓
Activar Sucursal
```

---

## Asignar Responsable

```text
Administrador
      ↓
Seleccionar Usuario
      ↓
Asignar Gerente
```

---

## Desactivar Sucursal

```text
Administrador
      ↓
Desactivar
      ↓
Mantener Historial
```

---

# Reglas de Negocio

## Regla 1

Toda sucursal debe pertenecer a una empresa.

---

## Regla 2

No se pueden crear sucursales que superen el límite del plan contratado.

---

## Regla 3

Las sucursales inactivas no pueden recibir nuevos tickets.

---

## Regla 4

La eliminación física de sucursales está prohibida.

Solo podrán marcarse como inactivas.

---

## Regla 5

Los usuarios solo visualizarán sucursales de su empresa.

---

# Auditoría

Registrar:

```text
Sucursal creada
Sucursal actualizada
Sucursal activada
Sucursal desactivada
Cambio de gerente
```

---

# Eventos

```text
BranchCreated
BranchUpdated
BranchActivated
BranchDeactivated
ManagerAssigned
```

---

# Testing

## Unit Tests

```text
BranchServiceTest
BranchRepositoryTest
```

---

## Feature Tests

```text
CreateBranchTest
UpdateBranchTest
DeactivateBranchTest
AssignManagerTest
```

---

# KPI del Módulo

* Total de sucursales.
* Sucursales activas.
* Tickets por sucursal.
* Inventario por sucursal.
* Rendimiento por sucursal.

---

# Integración con Otros Módulos

```text
Companies
Users
Customers
Devices
Tickets
Inventory
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Branches permitirá que IAtechs Pro opere bajo un modelo multi-sucursal empresarial, gestionando sedes, talleres y centros de servicio de forma centralizada, segura y escalable, manteniendo el aislamiento de datos por empresa y optimizando la operación distribuida.
