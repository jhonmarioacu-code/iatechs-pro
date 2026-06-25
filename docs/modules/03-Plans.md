# Module Specification

# IAtechs Pro

## Módulo: Plans

---

# Objetivo

Administrar los planes comerciales disponibles en IAtechs Pro.

Los planes determinan las funcionalidades, límites y capacidades que tendrá cada empresa dentro de la plataforma.

---

# Nombre Técnico

Plans

---

# Tabla Principal

plans

---

# Descripción

Los planes representan la oferta comercial de IAtechs Pro.

Cada suscripción deberá estar asociada a un plan.

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Company Owner

Visualización del plan contratado.

---

# Estados del Plan

## Active

Plan disponible para contratación.

```text
active
```

---

## Inactive

Plan deshabilitado.

```text
inactive
```

---

# Planes Iniciales

## Trial

Periodo gratuito de prueba.

---

## Starter

Pequeños talleres y técnicos independientes.

---

## Professional

Empresas de tamaño medio.

---

## Business

Empresas con múltiples técnicos y sucursales.

---

## Enterprise

Grandes organizaciones con requerimientos avanzados.

---

# Tabla plans

| Campo         | Tipo      | Descripción       |
| ------------- | --------- | ----------------- |
| id            | bigint    | Identificador     |
| name          | string    | Nombre            |
| slug          | string    | Código único      |
| description   | text      | Descripción       |
| monthly_price | decimal   | Precio mensual    |
| yearly_price  | decimal   | Precio anual      |
| max_users     | integer   | Máximo usuarios   |
| max_customers | integer   | Máximo clientes   |
| max_tickets   | integer   | Máximo tickets    |
| max_branches  | integer   | Máximo sucursales |
| has_inventory | boolean   | Inventario        |
| has_finance   | boolean   | Finanzas          |
| has_api       | boolean   | API               |
| has_ai        | boolean   | IA                |
| status        | string    | Estado            |
| created_at    | timestamp | Creación          |
| updated_at    | timestamp | Actualización     |

---

# Migración Oficial

```php
Schema::create('plans', function (Blueprint $table) {

    $table->id();

    $table->string('name');

    $table->string('slug')->unique();

    $table->text('description')->nullable();

    $table->decimal('monthly_price', 12, 2)->default(0);

    $table->decimal('yearly_price', 12, 2)->default(0);

    $table->integer('max_users')->default(1);

    $table->integer('max_customers')->default(100);

    $table->integer('max_tickets')->default(100);

    $table->integer('max_branches')->default(1);

    $table->boolean('has_inventory')->default(false);

    $table->boolean('has_finance')->default(false);

    $table->boolean('has_api')->default(false);

    $table->boolean('has_ai')->default(false);

    $table->enum('status', [
        'active',
        'inactive'
    ])->default('active');

    $table->timestamps();
});
```

---

# Modelo

Ubicación

```text
app/Models/Plan.php
```

---

# Fillable

```php
protected $fillable = [
    'name',
    'slug',
    'description',
    'monthly_price',
    'yearly_price',
    'max_users',
    'max_customers',
    'max_tickets',
    'max_branches',
    'has_inventory',
    'has_finance',
    'has_api',
    'has_ai',
    'status'
];
```

---

# Casts

```php
protected $casts = [
    'has_inventory' => 'boolean',
    'has_finance' => 'boolean',
    'has_api' => 'boolean',
    'has_ai' => 'boolean'
];
```

---

# Relaciones

## Subscriptions

Un plan puede tener múltiples suscripciones.

```php
public function subscriptions()
{
    return $this->hasMany(Subscription::class);
}
```

---

# Repository

Ubicación

```text
app/Repositories/PlanRepository.php
```

---

# Responsabilidades

* Crear planes.
* Actualizar planes.
* Consultar planes.
* Activar planes.
* Desactivar planes.

---

# Service

Ubicación

```text
app/Services/PlanService.php
```

---

# Responsabilidades

* Crear plan.
* Modificar límites.
* Validar disponibilidad.
* Gestionar funcionalidades.
* Gestionar precios.

---

# Request

## StorePlanRequest

Ubicación

```text
app/Http/Requests/Plan
```

---

# Validaciones

```php
return [

    'name' => [
        'required',
        'string',
        'max:255'
    ],

    'slug' => [
        'required',
        'unique:plans,slug'
    ],

    'monthly_price' => [
        'required',
        'numeric'
    ],

    'yearly_price' => [
        'required',
        'numeric'
    ]

];
```

---

# Policy

```text
PlanPolicy
```

---

# Permisos

```text
plans.view
plans.create
plans.update
plans.delete
```

---

# Endpoints Web

```http
GET     /plans
GET     /plans/create
POST    /plans
GET     /plans/{id}
GET     /plans/{id}/edit
PUT     /plans/{id}
DELETE  /plans/{id}
```

---

# Endpoints API

```http
GET     /api/v1/plans
POST    /api/v1/plans
GET     /api/v1/plans/{id}
PUT     /api/v1/plans/{id}
DELETE  /api/v1/plans/{id}
```

---

# Casos de Uso

## Crear Plan

```text
Super Admin
      ↓
Crear Plan
      ↓
Definir Límites
      ↓
Definir Funcionalidades
      ↓
Publicar Plan
```

---

## Actualizar Plan

```text
Super Admin
      ↓
Modificar Límites
      ↓
Guardar Cambios
```

---

# Reglas de Negocio

## Regla 1

Toda suscripción debe pertenecer a un plan.

---

## Regla 2

Un plan inactivo no puede asignarse a nuevas empresas.

---

## Regla 3

Los límites del plan deben validarse antes de permitir operaciones.

---

## Regla 4

Los cambios de plan no deben afectar el historial de suscripciones.

---

## Regla 5

Los precios deben almacenarse independientemente para mensual y anual.

---

# Auditoría

Registrar:

```text
Creación de plan
Actualización de plan
Cambio de precios
Activación
Desactivación
```

---

# Testing

## Unit Tests

```text
PlanServiceTest
PlanRepositoryTest
```

---

## Feature Tests

```text
CreatePlanTest
UpdatePlanTest
DeletePlanTest
```

---

# KPI del Módulo

* Total de planes activos.
* Empresas por plan.
* Ingresos por plan.
* Plan más contratado.
* Conversión Trial → Pago.

---

# Integración con Otros Módulos

```text
Companies
Subscriptions
Payments
Invoices
Analytics
```

---

# Resultado Esperado

El módulo Plans permitirá administrar la oferta comercial de IAtechs Pro, definiendo límites, funcionalidades y precios para cada tipo de cliente, garantizando una operación SaaS escalable, controlada y preparada para crecimiento empresarial.
