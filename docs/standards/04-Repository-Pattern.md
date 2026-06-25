# IAtechs Pro

# Development Standards

## 04-Repository-Pattern

---

# Objetivo

Definir el estándar oficial para la implementación del patrón Repository dentro de IAtechs Pro, garantizando desacoplamiento, mantenibilidad, reutilización y consistencia en el acceso a datos.

---

# Principio Fundamental

Los Services nunca deben acceder directamente a Eloquent Models.

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
    ↓
Database
```

---

# Beneficios

```text
Desacoplamiento

Reutilización

Testabilidad

Mantenibilidad

Escalabilidad
```

---

# Ubicación

Todos los repositorios deberán ubicarse dentro del dominio correspondiente.

Ejemplo:

```text
app/Domains/Tickets/Repositories

app/Domains/Customers/Repositories

app/Domains/Inventory/Repositories
```

---

# Convención de Nombre

Formato:

```text
EntityRepository
```

---

# Correcto

```php
TicketRepository

CustomerRepository

InvoiceRepository

ProductRepository
```

---

# Incorrecto

```php
RepositoryTicket

TicketsRepo

DataRepository
```

---

# Responsabilidad

Un Repository es responsable únicamente de:

```text
Persistencia

Consultas

Filtros

Búsquedas

Paginación
```

---

# Prohibido

```text
Business Logic

Autorización

Validación

Integraciones externas
```

---

# Ejemplo de Estructura

```text
Tickets

├── Controllers
├── Models
├── Services
├── Repositories
│   └── TicketRepository.php
├── Requests
├── Policies
└── Resources
```

---

# Constructor

Los repositorios deberán recibir el modelo mediante inyección.

Ejemplo:

```php
class TicketRepository
{
    public function __construct(
        protected Ticket $model
    ) {
    }
}
```

---

# Métodos Base Obligatorios

Todos los repositorios deben implementar como mínimo:

```php
find()

findOrFail()

all()

paginate()

create()

update()

delete()
```

---

# Ejemplo

```php
public function find(
    int $id
): ?Ticket
{
    return $this->model->find($id);
}
```

---

# Create

```php
public function create(
    array $data
): Ticket
{
    return $this->model->create($data);
}
```

---

# Update

```php
public function update(
    Ticket $ticket,
    array $data
): bool
{
    return $ticket->update($data);
}
```

---

# Delete

```php
public function delete(
    Ticket $ticket
): bool
{
    return $ticket->delete();
}
```

---

# Paginación

Formato oficial:

```php
public function paginate(
    int $perPage = 15
)
{
    return $this->model
        ->latest()
        ->paginate($perPage);
}
```

---

# Filtros

Los filtros deben vivir dentro del Repository.

---

# Correcto

```php
public function getOpenTickets()
{
    return $this->model
        ->where('status', 'open')
        ->get();
}
```

---

# Incorrecto

```php
Service
{
    Ticket::where(...)
}
```

---

# Multi Tenant

Todo Repository empresarial debe respetar:

```php
company_id
```

---

# Ejemplo

```php
public function findByCompany(
    int $companyId
)
{
    return $this->model
        ->where(
            'company_id',
            $companyId
        )
        ->get();
}
```

---

# Global Scope

Si existe:

```php
BelongsToCompany
```

el Repository no deberá repetir filtros innecesarios.

---

# Búsquedas

Formato:

```php
public function search(
    string $term
)
{
    return $this->model
        ->where(
            'name',
            'like',
            "%{$term}%"
        )
        ->get();
}
```

---

# Relaciones

Los eager loading deben definirse dentro del Repository.

---

# Correcto

```php
public function findWithRelations(
    int $id
)
{
    return $this->model
        ->with([
            'customer',
            'technician'
        ])
        ->findOrFail($id);
}
```

---

# Incorrecto

```php
Service
{
    Ticket::with(...)
}
```

---

# Queries Complejas

Toda consulta compleja debe vivir en Repository.

---

# Ejemplo

```php
public function getMonthlyRevenue(
    Carbon $from,
    Carbon $to
)
{
    return $this->model
        ->whereBetween(
            'created_at',
            [$from, $to]
        )
        ->sum('total');
}
```

---

# Repositories y DTOs

Los Repositories reciben:

```text
DTO

Array

Value Objects
```

---

# Nunca

```text
Request
```

---

# Incorrecto

```php
create(Request $request)
```

---

# Correcto

```php
create(array $data)
```

---

# Repositories y Services

El Service controla la lógica.

El Repository controla la persistencia.

---

# Correcto

```text
Service
 ↓
Repository
 ↓
Model
```

---

# Incorrecto

```text
Repository
 ↓
Business Logic
```

---

# Transacciones

Las transacciones deben iniciarse en Services.

---

# Correcto

```php
DB::transaction(
    function () {
        ...
    }
);
```

---

# Repository

No debe abrir transacciones.

---

# Cache

Opcionalmente los Repositories podrán implementar:

```php
Cache::remember()
```

para consultas pesadas.

---

# Auditoría

Los Repositories nunca generan auditorías.

---

# Correcto

```text
Service
 ↓
Audit Service
```

---

# Testing

Cada Repository debe tener:

```text
Unit Test
```

---

# Ejemplo

```text
tests/Unit/Tickets

TicketRepositoryTest.php
```

---

# Reglas Prohibidas

Nunca:

```php
Auth::user()
```

dentro del Repository.

---

Nunca:

```php
request()
```

dentro del Repository.

---

Nunca:

```php
session()
```

dentro del Repository.

---

Nunca:

```php
Gate

Policy

Permission
```

dentro del Repository.

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
Model
 ↓
Database
```

---

# Resultado Esperado

Todos los módulos de IAtechs Pro deberán utilizar Repository Pattern de forma consistente, garantizando separación de responsabilidades, facilidad de pruebas, reutilización de consultas y cumplimiento de los principios DDD establecidos en la plataforma.
