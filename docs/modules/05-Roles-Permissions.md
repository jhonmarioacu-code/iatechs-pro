# Module Specification

# IAtechs Pro

## Módulo: Roles & Permissions

---

# Objetivo

Administrar los roles, permisos y niveles de acceso dentro de IAtechs Pro.

Este módulo controla qué acciones puede realizar cada usuario dentro de la plataforma.

---

# Nombre Técnico

Roles & Permissions

---

# Tecnología Oficial

```text id="g4m2zn"
spatie/laravel-permission
```

---

# Descripción

El sistema utilizará un modelo RBAC (Role Based Access Control).

Cada usuario tendrá uno o varios roles.

Cada rol tendrá uno o varios permisos.

---

# Arquitectura

```text id="s8w5ke"
User
  ↓
Role
  ↓
Permissions
```

---

# Tablas Utilizadas

```text id="v5c3py"
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

---

# Roles Oficiales IAtechs Pro

## Super Admin

Acceso total a toda la plataforma.

---

## Company Owner

Propietario de la empresa.

Control completo de su organización.

---

## Administrator

Administrador operativo.

---

## Technical Manager

Jefe técnico.

---

## Receptionist

Recepción y atención al cliente.

---

## Inventory Manager

Gestión de inventario.

---

## Accountant

Gestión financiera.

---

## Technician

Técnico de reparación.

---

## Customer

Portal de cliente.

---

# Jerarquía de Roles

```text id="g1t8pr"
Super Admin

Company Owner

Administrator

Technical Manager

Receptionist

Inventory Manager

Accountant

Technician

Customer
```

---

# Modelo Role

Ubicación

```text id="f4n6qz"
app/Models/Role.php
```

---

# Modelo Permission

Ubicación

```text id="h3r7vk"
app/Models/Permission.php
```

---

# Integración User

```php id="p9x2cm"
use Spatie\Permission\Traits\HasRoles;
```

---

# Ejemplo

```php id="t7w4aq"
class User extends Authenticatable
{
    use HasRoles;
}
```

---

# Categorías de Permisos

## Companies

```text id="y6m8rb"
companies.view
companies.create
companies.update
companies.delete
companies.activate
companies.suspend
```

---

## Users

```text id="n2v5zp"
users.view
users.create
users.update
users.delete
users.suspend
```

---

## Customers

```text id="d5j8tw"
customers.view
customers.create
customers.update
customers.delete
```

---

## Devices

```text id="r7p3hn"
devices.view
devices.create
devices.update
devices.delete
```

---

## Tickets

```text id="m8q2fx"
tickets.view
tickets.create
tickets.update
tickets.delete
tickets.assign
tickets.close
```

---

## Diagnostics

```text id="w6r9ka"
diagnostics.view
diagnostics.create
diagnostics.update
```

---

## Quotes

```text id="j4n8ts"
quotes.view
quotes.create
quotes.update
quotes.approve
quotes.reject
```

---

## Repairs

```text id="z2m7vh"
repairs.view
repairs.create
repairs.update
repairs.complete
```

---

## Inventory

```text id="c7p4ry"
inventory.view
inventory.create
inventory.update
inventory.delete
```

---

## Purchases

```text id="x9q5du"
purchases.view
purchases.create
purchases.update
```

---

## Invoices

```text id="a5t8bn"
invoices.view
invoices.create
invoices.update
invoices.cancel
```

---

## Payments

```text id="u4r7kw"
payments.view
payments.create
payments.refund
```

---

## Reports

```text id="k2m9qe"
reports.view
reports.export
```

---

## AI

```text id="e7n5vc"
ai.view
ai.use
ai.admin
```

---

## Settings

```text id="b8w3jy"
settings.view
settings.update
```

---

# Asignación Inicial de Roles

## Super Admin

Todos los permisos.

---

## Company Owner

Todos los permisos de su empresa.

---

## Administrator

Gestión completa operativa.

---

## Technical Manager

```text id="n6k2rb"
tickets.*
diagnostics.*
repairs.*
```

---

## Receptionist

```text id="r9w4vz"
customers.*
devices.*
tickets.create
tickets.view
```

---

## Inventory Manager

```text id="p3j7mc"
inventory.*
purchases.*
```

---

## Accountant

```text id="v2x5dn"
invoices.*
payments.*
reports.view
```

---

## Technician

```text id="m4t8qk"
tickets.view
diagnostics.*
repairs.*
```

---

## Customer

```text id="q7p6ra"
portal.view
tickets.view.own
quotes.view.own
invoices.view.own
```

---

# Service

Ubicación

```text id="h5n2vu"
app/Services/RolePermissionService.php
```

---

# Responsabilidades

* Crear roles.
* Crear permisos.
* Asignar permisos.
* Revocar permisos.
* Sincronizar permisos.

---

# Repository

Ubicación

```text id="z8m4qy"
app/Repositories/RolePermissionRepository.php
```

---

# Middleware

## Rol

```php id="k3w9tn"
->middleware('role:Administrator')
```

---

## Permiso

```php id="y7n5bx"
->middleware('permission:tickets.create')
```

---

# Policies

Todas las entidades deberán implementar Policies.

Ejemplos:

```text id="f2v8ca"
CompanyPolicy
UserPolicy
CustomerPolicy
TicketPolicy
InvoicePolicy
```

---

# Reglas de Negocio

## Regla 1

Todo usuario debe poseer al menos un rol.

---

## Regla 2

Los permisos nunca deben validarse manualmente.

Siempre utilizar:

```php id="r6m3qt"
can()
hasRole()
hasPermissionTo()
```

---

## Regla 3

Los usuarios solo podrán acceder a datos de su empresa.

---

## Regla 4

Super Admin puede acceder a todas las empresas.

---

## Regla 5

Las Policies son obligatorias para módulos críticos.

---

# Auditoría

Registrar:

```text id="p8n4cv"
Creación de roles
Creación de permisos
Asignación de roles
Revocación de permisos
Cambios de acceso
```

---

# Eventos

```text id="t5k9rz"
RoleCreated
PermissionCreated
RoleAssigned
PermissionRevoked
```

---

# Testing

## Unit Tests

```text id="n4x7pw"
RolePermissionServiceTest
PermissionValidationTest
```

---

## Feature Tests

```text id="g9m2dv"
AssignRoleTest
AssignPermissionTest
AccessControlTest
PolicyTest
```

---

# KPI del Módulo

* Usuarios por rol.
* Permisos asignados.
* Accesos denegados.
* Cambios de permisos.
* Actividad administrativa.

---

# Integración con Otros Módulos

```text id="v3k8qn"
Users
Companies
Tickets
Inventory
Invoices
Payments
Reports
AI
Audit Logs
```

---

# Resultado Esperado

El módulo Roles & Permissions garantizará un control de acceso robusto y escalable en IAtechs Pro, permitiendo administrar usuarios, permisos y responsabilidades de forma segura, auditable y alineada con una arquitectura SaaS multiempresa enterprise.
