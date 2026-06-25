# IAtechs Pro

# Development Standards

## 01-Coding-Standards

---

# Objetivo

Definir los estándares oficiales de programación utilizados en IAtechs Pro para garantizar mantenibilidad, escalabilidad, seguridad, consistencia y calidad empresarial del código.

---

# Alcance

Aplica a:

```text
Backend

Frontend

APIs

Jobs

Events

Services

Repositories

Tests

AI Modules
```

---

# Stack Oficial

## Backend

```text
PHP 8.4
Laravel 12
```

---

## Database

```text
PostgreSQL
```

---

## Cache

```text
Redis
```

---

## Queue

```text
Laravel Horizon
```

---

## Cloud

```text
AWS
```

---

## Frontend

```text
Blade

Livewire

AlpineJS

TailwindCSS
```

---

# Principios Generales

Todo código debe cumplir:

```text
SOLID

DRY

KISS

YAGNI

Clean Code
```

---

# Reglas de Código

## Regla 1

Una clase debe tener una única responsabilidad.

---

## Regla 2

No duplicar lógica.

---

## Regla 3

No realizar consultas SQL en Views.

---

## Regla 4

No usar lógica de negocio en Controllers.

---

## Regla 5

Toda lógica compleja debe vivir en Services.

---

# Tipado Estricto

Todos los archivos PHP deberán iniciar con:

```php
<?php

declare(strict_types=1);
```

---

# Namespace

Todo namespace debe reflejar la ubicación real.

Ejemplo:

```php
namespace App\Domains\Tickets\Services;
```

---

# Clases

## Correcto

```php
class TicketService
{
}
```

---

## Incorrecto

```php
class ticketservice
{
}
```

---

# Métodos

Usar camelCase.

## Correcto

```php
createTicket()

closeTicket()

calculateInvoice()
```

---

## Incorrecto

```php
CreateTicket()

create_ticket()
```

---

# Variables

Usar camelCase.

## Correcto

```php
$customerId

$ticketStatus
```

---

## Incorrecto

```php
$CustomerID

$ticket_status
```

---

# Constantes

Usar UPPER_CASE.

```php
const MAX_RETRIES = 3;
```

---

# Controladores

Ubicación:

```text
app/Domains/*/Controllers
```

---

# Responsabilidad

Solo:

```text
Recibir Request

Autorizar

Llamar Service

Retornar Response
```

---

# Ejemplo

```php
public function store(
    StoreTicketRequest $request
)
{
    return $this->service
        ->create($request->validated());
}
```

---

# Services

Ubicación:

```text
app/Domains/*/Services
```

---

# Responsabilidad

Toda lógica de negocio.

---

# Repositories

Ubicación:

```text
app/Domains/*/Repositories
```

---

# Responsabilidad

Acceso a datos.

---

# Requests

Ubicación:

```text
app/Domains/*/Requests
```

---

# Responsabilidad

Validación.

---

# Policies

Ubicación:

```text
app/Domains/*/Policies
```

---

# Responsabilidad

Autorización.

---

# Models

Ubicación:

```text
app/Domains/*/Models
```

---

# Regla

Nunca colocar lógica compleja.

Solo:

```text
Relationships

Scopes

Accessors

Mutators
```

---

# Base de Datos

## Tablas

Usar plural.

```text
users

tickets

customers

companies
```

---

## Columnas

Usar snake_case.

```text
company_id

created_at

updated_at
```

---

# Foreign Keys

Formato:

```text
entity_id
```

Ejemplo:

```text
company_id

customer_id

ticket_id
```

---

# Migraciones

Una responsabilidad por migración.

## Correcto

```text
create_tickets_table

add_status_to_tickets_table
```

---

# APIs

Formato:

```text
/api/v1/
```

---

## Ejemplo

```text
/api/v1/tickets
```

---

# Responses

Siempre JSON estandarizado.

```json
{
  "success": true,
  "message": "Created",
  "data": {}
}
```

---

# Manejo de Errores

Nunca:

```php
die();

dd();

dump();
```

---

Usar:

```php
Log::error();

throw new Exception();
```

---

# Logging

Ubicación:

```text
storage/logs
```

---

# Registrar

```text
Errors

Security

AI

Payments

Queues
```

---

# Eventos

Ubicación:

```text
app/Events
```

---

# Naming

```php
TicketCreated

InvoicePaid

CustomerRegistered
```

---

# Jobs

Ubicación:

```text
app/Jobs
```

---

# Naming

```php
GenerateInvoiceJob

SyncInventoryJob

ProcessAIRequestJob
```

---

# Tests

Ubicación:

```text
tests/
```

---

# Estructura

```text
Unit

Feature
```

---

# Cobertura Mínima

```text
80%
```

---

# Seguridad

Nunca almacenar:

```text
Passwords

Tokens

Secrets
```

en texto plano.

---

# Variables Sensibles

Siempre usar:

```env
.env
```

---

# Multi Tenant

Toda consulta empresarial debe respetar:

```php
company_id
```

---

# AI Standards

Toda integración IA deberá:

```text
Registrar consumo

Registrar proveedor

Registrar tokens

Registrar costos
```

---

# Comentarios

Evitar comentarios innecesarios.

---

## Correcto

```php
// Calcular impuesto compuesto
```

---

## Incorrecto

```php
// Sumar dos números
```

---

# Formato

Estándar:

```text
PSR-12
```

---

# Herramientas Obligatorias

```text
Laravel Pint

PHPStan

Pest

Laravel Debugbar (Local)

Laravel Telescope (Development)
```

---

# Revisión de Código

Todo Pull Request debe validar:

```text
Coding Standards

Testing

Security

Performance

Tenant Isolation
```

---

# Reglas Prohibidas

Nunca usar:

```php
DB::table()
```

cuando exista Repository.

---

Nunca usar:

```php
Auth::user()
```

directamente en Services.

---

Nunca realizar:

```php
Queries en Blade
```

---

# Resultado Esperado

Todo desarrollo realizado dentro de IAtechs Pro deberá seguir estos estándares para garantizar un código uniforme, mantenible, seguro, escalable y preparado para entornos Enterprise SaaS Multi-Tenant.
