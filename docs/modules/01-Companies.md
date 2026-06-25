# Module Specification

# IAtechs Pro

## Módulo: Companies

---

# Objetivo

Administrar las empresas registradas dentro de IAtechs Pro.

Cada empresa representa un tenant independiente dentro de la plataforma SaaS y posee sus propios usuarios, clientes, tickets, inventario, facturación y configuraciones.

---

# Nombre Técnico

```text
Companies
```

---

# Tabla Principal

```text
companies
```

---

# Descripción

Una empresa es la entidad raíz de todo el sistema.

Toda información operativa deberá pertenecer obligatoriamente a una empresa.

---

# Alcance

El módulo permite:

* Crear empresas.
* Editar empresas.
* Suspender empresas.
* Activar empresas.
* Cancelar empresas.
* Gestionar configuración general.
* Asociar planes y suscripciones.

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Company Owner

Acceso únicamente a su empresa.

---

## Administrator

Acceso según permisos asignados.

---

# Estados de Empresa

## Active

```text
active
```

Empresa operativa.

---

## Suspended

```text
suspended
```

Empresa bloqueada temporalmente.

---

## Cancelled

```text
cancelled
```

Empresa cancelada.

---

# Campos Principales

## Tabla companies

| Campo      | Tipo      |
| ---------- | --------- |
| id         | bigint    |
| name       | string    |
| legal_name | string    |
| tax_id     | string    |
| email      | string    |
| phone      | string    |
| website    | string    |
| logo       | string    |
| status     | string    |
| plan_id    | bigint    |
| created_at | timestamp |
| updated_at | timestamp |

---

# Migración

```php
Schema::create('companies', function (Blueprint $table) {

    $table->id();

    $table->string('name');

    $table->string('legal_name')->nullable();

    $table->string('tax_id')->nullable();

    $table->string('email')->nullable();

    $table->string('phone')->nullable();

    $table->string('website')->nullable();

    $table->string('logo')->nullable();

    $table->enum('status', [
        'active',
        'suspended',
        'cancelled'
    ])->default('active');

    $table->timestamps();
});
```

---

# Relaciones

## Company → Users

```text
1:N
```

Una empresa tiene muchos usuarios.

---

## Company → Customers

```text
1:N
```

---

## Company → Devices

```text
1:N
```

---

## Company → Tickets

```text
1:N
```

---

## Company → Products

```text
1:N
```

---

## Company → Invoices

```text
1:N
```

---

## Company → Branches

```text
1:N
```

---

## Company → Subscription

```text
1:1
```

---

# Modelo

## Company

Ubicación:

```text
app/Models/Company.php
```

---

# Fillable

```php
protected $fillable = [
    'name',
    'legal_name',
    'tax_id',z
    'email',
    'phone',
    'website',
    'logo',
    'status'
];
```

---

# Relaciones del Modelo

```php
users()
customers()
devices()
tickets()
products()
invoices()
branches()
subscription()
```

---

# Repository

```text
app/Repositories/CompanyRepository.php
```

---

# Responsabilidades

* Buscar empresas.
* Crear empresas.
* Actualizar empresas.
* Filtrar empresas.

---

# Service

```text
app/Services/CompanyService.php
```

---

# Responsabilidades

* Crear empresa.
* Activar empresa.
* Suspender empresa.
* Cancelar empresa.
* Crear configuración inicial.
* Crear suscripción inicial.

---

# Request

## StoreCompanyRequest

```text
app/Http/Requests/Company
```

---

## Reglas

```php
'name' => ['required','string','max:255'],
'email' => ['nullable','email'],
'phone' => ['nullable','string'],
'tax_id' => ['nullable','string']
```

---

# Policy

```text
CompanyPolicy
```

---

# Permisos

```text
companies.view
companies.create
companies.update
companies.delete
companies.activate
companies.suspend
```

---

# Endpoints Web

```http
GET     /companies
GET     /companies/create
POST    /companies
GET     /companies/{id}
GET     /companies/{id}/edit
PUT     /companies/{id}
```

---

# Endpoints API

```http
GET     /api/v1/companies
POST    /api/v1/companies
GET     /api/v1/companies/{id}
PUT     /api/v1/companies/{id}
DELETE  /api/v1/companies/{id}
```

---

# Casos de Uso

## Crear Empresa

Flujo:

```text
Super Admin
      ↓
Crear Empresa
      ↓
Generar Configuración
      ↓
Crear Suscripción
      ↓
Crear Owner
      ↓
Empresa Activa
```

---

## Suspender Empresa

Flujo:

```text
Super Admin
      ↓
Suspender Empresa
      ↓
Bloquear Acceso
      ↓
Mantener Datos
```

---

## Cancelar Empresa

Flujo:

```text
Super Admin
      ↓
Cancelar
      ↓
Desactivar Acceso
      ↓
Conservar Historial
```

---

# Reglas de Negocio

## Regla 1

Toda empresa debe tener una suscripción.

---

## Regla 2

Toda empresa debe tener un propietario.

---

## Regla 3

Una empresa suspendida no puede iniciar sesión.

---

## Regla 4

Una empresa cancelada no puede operar.

---

## Regla 5

No se puede eliminar una empresa con información asociada.

---

# Auditoría

Eventos auditables:

```text
Empresa creada
Empresa actualizada
Empresa suspendida
Empresa activada
Empresa cancelada
```

---

# Testing

## Unit Tests

```text
CompanyServiceTest
CompanyRepositoryTest
```

---

## Feature Tests

```text
CreateCompanyTest
UpdateCompanyTest
SuspendCompanyTest
```

---

# KPI del Módulo

* Empresas activas.
* Empresas suspendidas.
* Empresas canceladas.
* Empresas por plan.
* Nuevas empresas por mes.

---

# Resultado Esperado

El módulo Companies será el núcleo multiempresa de IAtechs Pro, garantizando el aislamiento de datos, la administración de clientes SaaS y el control centralizado de las organizaciones registradas en la plataforma.
