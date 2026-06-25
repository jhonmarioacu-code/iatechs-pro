# Module Specification

# IAtechs Pro

## Módulo: Subscriptions

---

# Objetivo

Administrar las suscripciones SaaS de las empresas registradas en IAtechs Pro.

Este módulo controla:

* Plan contratado.
* Estado de la suscripción.
* Fechas de inicio y vencimiento.
* Renovaciones.
* Cancelaciones.
* Restricciones de acceso a la plataforma.

Toda empresa deberá tener una suscripción válida para utilizar IAtechs Pro.

---

# Nombre Técnico

Subscriptions

---

# Tabla Principal

subscriptions

---

# Dependencias

Este módulo depende de:

* Companies
* Plans
* Payments

---

# Roles con Acceso

## Super Admin

Acceso total.

## Company Owner

Visualización y gestión de la suscripción de su empresa.

## Administrator

Acceso según permisos asignados.

---

# Estados de Suscripción

## Trial

Periodo de prueba.

```text
trial
```

## Active

Suscripción activa.

```text
active
```

## Expired

Suscripción vencida.

```text
expired
```

## Suspended

Suspendida por incumplimiento.

```text
suspended
```

## Cancelled

Cancelada definitivamente.

```text
cancelled
```

---

# Tabla subscriptions

| Campo        | Tipo               | Descripción     |
| ------------ | ------------------ | --------------- |
| id           | bigint             | Identificador   |
| company_id   | bigint             | Empresa         |
| plan_id      | bigint             | Plan contratado |
| status       | string             | Estado          |
| starts_at    | timestamp          | Inicio          |
| expires_at   | timestamp          | Vencimiento     |
| cancelled_at | timestamp nullable | Cancelación     |
| created_at   | timestamp          | Creación        |
| updated_at   | timestamp          | Actualización   |

---

# Migración Oficial

```php
Schema::create('subscriptions', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->foreignId('plan_id')
        ->constrained('plans');

    $table->enum('status', [
        'trial',
        'active',
        'expired',
        'suspended',
        'cancelled'
    ])->default('trial');

    $table->timestamp('starts_at');

    $table->timestamp('expires_at');

    $table->timestamp('cancelled_at')->nullable();

    $table->timestamps();
});
```

---

# Relaciones

## Company

Una empresa posee una suscripción activa.

```php
public function company()
{
    return $this->belongsTo(Company::class);
}
```

---

## Plan

Una suscripción pertenece a un plan.

```php
public function plan()
{
    return $this->belongsTo(Plan::class);
}
```

---

## Payments

Una suscripción puede tener múltiples pagos.

```php
public function payments()
{
    return $this->hasMany(Payment::class);
}
```

---

# Modelo

Ubicación:

```text
app/Models/Subscription.php
```

---

# Fillable

```php
protected $fillable = [
    'company_id',
    'plan_id',
    'status',
    'starts_at',
    'expires_at',
    'cancelled_at'
];
```

---

# Casts

```php
protected $casts = [
    'starts_at' => 'datetime',
    'expires_at' => 'datetime',
    'cancelled_at' => 'datetime'
];
```

---

# Repository

Ubicación:

```text
app/Repositories/SubscriptionRepository.php
```

---

# Responsabilidades

* Crear suscripciones.
* Consultar suscripciones.
* Renovar suscripciones.
* Cancelar suscripciones.
* Verificar vencimientos.

---

# Service

Ubicación:

```text
app/Services/SubscriptionService.php
```

---

# Responsabilidades

* Activar plan.
* Renovar suscripción.
* Cancelar suscripción.
* Suspender acceso.
* Reactivar empresa.
* Validar vencimientos.

---

# Request

## StoreSubscriptionRequest

Ubicación:

```text
app/Http/Requests/Subscription
```

---

# Reglas de Validación

```php
return [

    'company_id' => [
        'required',
        'exists:companies,id'
    ],

    'plan_id' => [
        'required',
        'exists:plans,id'
    ],

    'starts_at' => [
        'required',
        'date'
    ],

    'expires_at' => [
        'required',
        'date',
        'after:starts_at'
    ]

];
```

---

# Policy

```text
SubscriptionPolicy
```

---

# Permisos

```text
subscriptions.view
subscriptions.create
subscriptions.update
subscriptions.cancel
subscriptions.renew
```

---

# Endpoints Web

```http
GET     /subscriptions
GET     /subscriptions/create
POST    /subscriptions
GET     /subscriptions/{id}
GET     /subscriptions/{id}/edit
PUT     /subscriptions/{id}
```

---

# Endpoints API

```http
GET     /api/v1/subscriptions
POST    /api/v1/subscriptions
GET     /api/v1/subscriptions/{id}
PUT     /api/v1/subscriptions/{id}

POST    /api/v1/subscriptions/{id}/renew
POST    /api/v1/subscriptions/{id}/cancel
```

---

# Casos de Uso

## Crear Suscripción

```text
Crear Empresa
      ↓
Asignar Plan
      ↓
Crear Suscripción
      ↓
Activar Empresa
```

---

## Renovar Suscripción

```text
Pago Confirmado
      ↓
Actualizar Fecha
      ↓
Mantener Estado Active
```

---

## Suspensión Automática

```text
Suscripción Vencida
      ↓
Job Programado
      ↓
Cambiar Estado Expired
      ↓
Suspender Empresa
```

---

# Jobs

## CheckExpiredSubscriptionsJob

Ubicación:

```text
app/Jobs/CheckExpiredSubscriptionsJob.php
```

Función:

* Verificar suscripciones vencidas.
* Cambiar estado.
* Suspender empresas asociadas.

---

# Eventos

```text
SubscriptionCreated
SubscriptionRenewed
SubscriptionExpired
SubscriptionCancelled
```

---

# Reglas de Negocio

## Regla 1

Toda empresa debe tener una suscripción.

---

## Regla 2

Una empresa sin suscripción activa no podrá acceder al sistema.

---

## Regla 3

Solo puede existir una suscripción activa por empresa.

---

## Regla 4

Una suscripción vencida suspende automáticamente la empresa.

---

## Regla 5

La cancelación no elimina los datos históricos.

---

## Regla 6

Las renovaciones extienden la fecha de vencimiento.

---

# Auditoría

Registrar:

```text
Creación
Renovación
Cambio de Plan
Suspensión
Reactivación
Cancelación
```

---

# Testing

## Unit Tests

```text
SubscriptionServiceTest
SubscriptionRepositoryTest
```

---

## Feature Tests

```text
CreateSubscriptionTest
RenewSubscriptionTest
CancelSubscriptionTest
ExpireSubscriptionTest
```

---

# KPI del Módulo

* Suscripciones activas.
* Suscripciones vencidas.
* Empresas en periodo trial.
* Renovaciones mensuales.
* Cancelaciones mensuales.
* Ingresos recurrentes mensuales (MRR).
* Churn Rate.

---

# Integración con Otros Módulos

```text
Companies
Plans
Payments
Invoices
Notifications
Analytics
Audit Logs
```

---

# Resultado Esperado

El módulo Subscriptions será responsable de controlar el ciclo de vida comercial de las empresas dentro de IAtechs Pro, garantizando acceso seguro, gestión de planes, renovaciones automáticas y control completo de licenciamiento SaaS bajo una arquitectura enterprise multiempresa.
