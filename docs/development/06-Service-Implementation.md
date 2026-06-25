# IAtechs Pro

# Development

## 06-Service-Implementation

---

# Objetivo

Definir la implementación oficial de la capa Service utilizada en IAtechs Pro para encapsular toda la lógica de negocio de los dominios del sistema.

---

# Alcance

Aplica a:

```text
Todos los módulos

Todos los dominios

API

Dashboard

Jobs

AI

Multi-Tenant
```

---

# Principio Fundamental

Los Services contienen las reglas de negocio.

---

# Responsabilidades

```text
Casos de uso

Procesos de negocio

Orquestación

Validaciones complejas

Transacciones

Eventos
```

---

# Prohibido

```text
Consultas SQL

Acceso directo a BD

Lógica de UI

Validaciones básicas
```

---

# Flujo Oficial

```text
Request
   ↓
Controller
   ↓
Service
   ↓
Repository
   ↓
Model
```

---

# Nunca

```text
Controller
    ↓
Model
```

---

# Ubicación

Ejemplo:

```text
app/Domains/Customers/Services
```

---

# Estructura

```text
Customers/

└── Services/
    ├── CustomerService.php
    └── Contracts/
```

---

# Convención

```text
CustomerService

TicketService

InvoiceService

InventoryService

CRMService
```

---

# Interface

Cada Service debe tener contrato.

---

# Ejemplo

```text
CustomerServiceInterface.php
```

---

# Registro

Registrar bindings en:

```text
DomainServiceProvider
```

---

# Ejemplo

```php
$this->app->bind(
    CustomerServiceInterface::class,
    CustomerService::class
);
```

---

# Inyección

Los Services dependen de:

```text
Repositories

DTOs

Actions

Events
```

---

# Ejemplo

```php
public function __construct(
    CustomerRepositoryInterface $repository
)
{
    $this->repository = $repository;
}
```

---

# Casos de Uso

Ejemplo:

```text
Create Customer

Update Customer

Deactivate Customer

Restore Customer
```

---

# Métodos Recomendados

```php
create()

update()

delete()

restore()

activate()

deactivate()
```

---

# Ejemplo

```php
public function create(
    array $data
);
```

---

# Create Flow

```text
Validate Business Rules
        ↓
Repository Create
        ↓
Dispatch Event
        ↓
Return Result
```

---

# Update Flow

```text
Find Entity
      ↓
Validate Rules
      ↓
Update Entity
      ↓
Dispatch Event
```

---

# Delete Flow

```text
Find Entity
      ↓
Validate Rules
      ↓
Soft Delete
      ↓
Dispatch Event
```

---

# Transacciones

Cuando existan múltiples operaciones:

```php
DB::transaction(
    function () {

    }
);
```

---

# Ejemplo

```php
Create Invoice

Create Payment

Create Audit
```

en una sola transacción.

---

# DTO

Los Services deben recibir DTOs cuando sea posible.

---

# Ejemplo

```php
CustomerData
```

---

# Actions

Delegar procesos complejos.

---

# Ejemplo

```php
ImportCustomerAction

GenerateInvoiceAction

SyncInventoryAction
```

---

# Eventos

Los Services disparan eventos.

---

# Ejemplo

```php
CustomerCreated

CustomerUpdated

InvoiceGenerated
```

---

# Listeners

Los Services NO ejecutan correos directamente.

---

# Correcto

```text
Service
   ↓
Event
   ↓
Listener
   ↓
Email
```

---

# Incorrecto

```text
Service
   ↓
Email
```

---

# Multi-Tenant

Todos los Services deben respetar:

```text
company_id
```

---

# Regla

Nunca acceder a otro tenant.

---

# Ejemplo

```php
tenant()->id()
```

---

# Ownership Validation

```php
if (
    $model->company_id !== tenant()->id()
) {
    throw new UnauthorizedException();
}
```

---

# Auditoría

Los Services generan registros auditables.

---

# Ejemplo

```text
Create

Update

Delete

Restore

Status Change
```

---

# Estados

Cambios de estado deben vivir aquí.

---

# Ejemplo

```php
activate()

suspend()

cancel()
```

---

# AI Services

Ubicación:

```text
app/AI/Services
```

---

# Responsabilidad

```text
Prompt Execution

Embeddings

Knowledge Base

Provider Routing
```

---

# Integraciones

Los Services pueden usar:

```text
AWS

S3

Redis

Payments

AI
```

mediante Infrastructure Layer.

---

# Nunca

```text
Service
    ↓
SDK Externo
```

directamente.

---

# Correcto

```text
Service
    ↓
Infrastructure
    ↓
SDK
```

---

# Excepciones

Todos los errores deben ser tipados.

---

# Ejemplo

```php
CustomerNotFoundException

InvoiceAlreadyPaidException

StockUnavailableException
```

---

# Testing

Cada Service debe tener:

```text
Unit Test
```

---

# Ejemplo

```text
CustomerServiceTest

InvoiceServiceTest

InventoryServiceTest
```

---

# Casos de Prueba

```text
Create

Update

Delete

Restore

Business Rules

Exceptions
```

---

# Métricas

Validar:

```text
Execution Time

Error Rate

Transactions
```

---

# Objetivo

```text
< 500 ms
```

por operación estándar.

---

# Dependencias Permitidas

```text
Service
    ↓
Repository

Service
    ↓
Action

Service
    ↓
Event
```

---

# Dependencias Prohibidas

```text
Service
    ↓
Controller

Service
    ↓
View

Service
    ↓
Request
```

---

# Principios

```text
SOLID

DRY

DDD

Service Layer
```

---

# Resultado Esperado

Todos los módulos de IAtechs Pro deberán implementar una capa Service centralizada, desacoplada y orientada a casos de uso, permitiendo encapsular las reglas de negocio de forma consistente, testeable y escalable dentro de la arquitectura SaaS Multi-Tenant Enterprise.
