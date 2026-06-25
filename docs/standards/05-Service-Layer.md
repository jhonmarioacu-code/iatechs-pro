# IAtechs Pro

# Development Standards

## 05-Service-Layer

---

# Objetivo

Definir el estándar oficial para la capa Service dentro de IAtechs Pro, garantizando separación de responsabilidades, reutilización de lógica de negocio y cumplimiento de la arquitectura DDD.

---

# Definición

La capa Service contiene:

```text
Business Logic

Workflows

Transactions

Domain Rules

Business Processes
```

---

# Principio Fundamental

Toda lógica de negocio debe vivir en Services.

---

# Arquitectura Oficial

```text
Controller
    ↓
Service
    ↓
Repository
    ↓
Model
```

---

# Responsabilidades

Los Services son responsables de:

```text
Aplicar reglas de negocio

Coordinar repositorios

Ejecutar procesos

Disparar eventos

Abrir transacciones

Coordinar dominios
```

---

# Prohibido

```text
Renderizar vistas

Validar Requests

Autorizar usuarios

Responder JSON
```

---

# Ubicación

```text
app/Domains/{Domain}/Services
```

---

# Ejemplo

```text
app/Domains/Tickets/Services

TicketService.php
```

---

# Convención de Nombre

Formato:

```text
EntityService
```

---

# Correcto

```php
TicketService

InvoiceService

CustomerService

InventoryService
```

---

# Incorrecto

```php
TicketManager

TicketHelper

TicketProcessor
```

---

# Constructor

Los Services reciben dependencias mediante inyección.

Ejemplo:

```php
class TicketService
{
    public function __construct(
        protected TicketRepository $tickets
    ) {
    }
}
```

---

# Flujo Oficial

```text
Request
 ↓
Validation
 ↓
DTO
 ↓
Service
 ↓
Repository
 ↓
Database
```

---

# Caso de Uso

## Crear Ticket

```text
Controller
 ↓
TicketService
 ↓
TicketRepository
 ↓
Ticket Model
```

---

# Ejemplo

```php
public function create(
    array $data
)
{
    return $this->tickets
        ->create($data);
}
```

---

# Regla

Un Service representa:

```text
Un caso de negocio
```

---

# Ejemplos

```text
Crear Ticket

Cerrar Ticket

Generar Factura

Registrar Pago

Asignar Técnico
```

---

# Services y Repositories

Los Services utilizan Repositories.

---

# Correcto

```php
$this->ticketRepository
     ->create($data);
```

---

# Incorrecto

```php
Ticket::create($data);
```

---

# Services y Models

Los Services nunca acceden directamente a Eloquent.

---

# Incorrecto

```php
Ticket::where(...)
```

---

# Correcto

```php
$this->ticketRepository
     ->find(...)
```

---

# Services y Requests

Los Services nunca reciben Requests.

---

# Incorrecto

```php
public function create(
    Request $request
)
```

---

# Correcto

```php
public function create(
    array $data
)
```

---

# Services y Auth

Los Services nunca deben depender de:

```php
Auth::user()
```

---

# Incorrecto

```php
$user = Auth::user();
```

---

# Correcto

```php
create(
    array $data,
    int $userId
)
```

---

# Services y Policies

La autorización se ejecuta antes del Service.

---

# Correcto

```text
Controller
 ↓
Policy
 ↓
Service
```

---

# Incorrecto

```text
Service
 ↓
Policy
```

---

# Transacciones

Toda transacción debe iniciarse en Services.

---

# Ejemplo

```php
DB::transaction(
    function () use ($data) {

        $ticket = $this->tickets
            ->create($data);

        event(
            new TicketCreated($ticket)
        );
    }
);
```

---

# Eventos

Los Services son responsables de disparar eventos.

---

# Ejemplo

```php
event(
    new InvoicePaid($invoice)
);
```

---

# Eventos Permitidos

```text
TicketCreated

InvoicePaid

CustomerCreated

PaymentRegistered
```

---

# Jobs

Los Services pueden despachar Jobs.

---

# Ejemplo

```php
GenerateInvoicePDFJob::dispatch(
    $invoice->id
);
```

---

# Services y Multi Tenant

Todos los Services deben respetar:

```text
company_id
```

---

# Regla

Ningún Service podrá acceder a datos de otra empresa.

---

# Services entre Dominios

Permitido:

```text
CustomerService

InventoryService

AccountingService
```

---

# Ejemplo

```text
TicketService
 ↓
CustomerService
```

---

# Prohibido

```text
TicketRepository
 ↓
CustomerRepository
```

---

# Domain Coordination

Los Services coordinan múltiples dominios.

---

# Ejemplo

```text
InvoiceService

 ↓

InventoryService

 ↓

AccountingService

 ↓

NotificationService
```

---

# DTO

Los Services deben trabajar con DTOs cuando el flujo sea complejo.

---

# Ejemplo

```php
CreateInvoiceDTO

CreateTicketDTO

RegisterPaymentDTO
```

---

# Return Types

Todos los métodos deben definir retorno.

---

# Correcto

```php
public function create(
    array $data
): Ticket
{
}
```

---

# Incorrecto

```php
public function create(
    array $data
)
{
}
```

---

# Logging

El Service puede registrar eventos críticos.

---

# Ejemplo

```php
Log::info(
    'Invoice generated'
);
```

---

# Casos Permitidos

```text
Payments

AI

Security

Billing

Critical Operations
```

---

# Excepciones

Los Services deben lanzar excepciones de negocio.

---

# Ejemplo

```php
throw new BusinessException(
    'Ticket already closed.'
);
```

---

# Prohibido

```php
die();

dd();

dump();
```

---

# Testing

Cada Service debe tener:

```text
Unit Test

Feature Test
```

---

# Ejemplo

```text
tests/Unit/Tickets

TicketServiceTest.php
```

---

# Reglas Prohibidas

Nunca:

```php
request()
```

---

Nunca:

```php
Auth::user()
```

---

Nunca:

```php
session()
```

---

Nunca:

```php
view()
```

---

Nunca:

```php
response()->json()
```

---

Nunca:

```php
Ticket::create()
```

directamente.

---

# Flujo Empresarial Oficial

```text
Controller
 ↓
Policy
 ↓
Validation
 ↓
DTO
 ↓
Service
 ↓
Repository
 ↓
Database

 ↓

Events
Jobs
Notifications
Audit
```

---

# Resultado Esperado

Todos los procesos de negocio de IAtechs Pro deberán ejecutarse exclusivamente a través de la capa Service, garantizando separación de responsabilidades, reutilización, testabilidad, cumplimiento DDD y escalabilidad Enterprise Multi-Tenant.
