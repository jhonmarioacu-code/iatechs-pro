# IAtechs Pro

# Architecture

## 04-Permissions-Architecture.md

---

# Objetivo

Definir la arquitectura oficial de autorización, roles y permisos de IAtechs Pro para garantizar seguridad, control de acceso granular y aislamiento empresarial Multi-Tenant.

---

# Tecnologías

## Laravel

```text
Laravel 12
```

---

## Permisos

```text
Spatie Laravel Permission
```

---

# Arquitectura de Seguridad

```text
Usuario
   ↓
Role
   ↓
Permissions
   ↓
Policies
   ↓
Middleware
   ↓
Acceso al Recurso
```

---

# Componentes

```text
Roles
Permissions
Policies
Middleware
Guards
Tenant Validation
Audit Logs
```

---

# Tablas Principales

```text
roles
permissions
model_has_roles
model_has_permissions
role_has_permissions
```

---

# Roles Globales

## SaaS

```text
super_admin
```

Control total de la plataforma.

---

# Roles Empresariales

## Owner

```text
owner
```

Propietario de empresa.

---

## Manager

```text
manager
```

Administrador operativo.

---

## Receptionist

```text
receptionist
```

Recepción y atención.

---

## Technician

```text
technician
```

Técnico de soporte.

---

## Inventory Manager

```text
inventory_manager
```

Gestión de inventario.

---

## Accountant

```text
accountant
```

Facturación y pagos.

---

## Customer

```text
customer
```

Cliente final.

---

# Jerarquía

```text
Super Admin
    ↓
Owner
    ↓
Manager
    ↓
Receptionist
    ↓
Technician
    ↓
Customer
```

---

# Convención de Permisos

Formato:

```text
modulo.accion
```

---

# Ejemplos

```text
tickets.view
tickets.create
tickets.update
tickets.delete

customers.view
customers.create
customers.update
customers.delete
```

---

# Permisos por Módulo

## Companies

```text
companies.view
companies.create
companies.update
companies.delete
companies.manage
```

---

## Users

```text
users.view
users.create
users.update
users.delete
users.manage
```

---

## Customers

```text
customers.view
customers.create
customers.update
customers.delete
```

---

## Devices

```text
devices.view
devices.create
devices.update
devices.delete
```

---

## Tickets

```text
tickets.view
tickets.create
tickets.update
tickets.delete
tickets.assign
tickets.close
```

---

## Diagnostics

```text
diagnostics.view
diagnostics.create
diagnostics.update
```

---

## Repairs

```text
repairs.view
repairs.create
repairs.update
repairs.complete
```

---

## Inventory

```text
inventory.view
inventory.create
inventory.update
inventory.delete
inventory.adjust
```

---

## Purchases

```text
purchases.view
purchases.create
purchases.approve
```

---

## Invoices

```text
invoices.view
invoices.create
invoices.update
invoices.cancel
```

---

## Payments

```text
payments.view
payments.create
payments.refund
```

---

## Reports

```text
reports.view
reports.export
```

---

## Analytics

```text
analytics.view
analytics.export
```

---

## Audit Logs

```text
audit_logs.view
audit_logs.export
```

---

## AI Assistant

```text
ai.view
ai.chat
ai.analytics
ai.automation
```

---

## System Settings

```text
settings.view
settings.update
settings.security
settings.integrations
settings.admin
```

---

# Policies

Ubicación:

```text
app/Policies/
```

---

# Policies Obligatorias

```text
CompanyPolicy
UserPolicy
CustomerPolicy
DevicePolicy
TicketPolicy
RepairPolicy
InvoicePolicy
PaymentPolicy
ReportPolicy
```

---

# Ejemplo

```php
public function update(
    User $user,
    Ticket $ticket
) {
    return $user->can('tickets.update')
        && $user->company_id === $ticket->company_id;
}
```

---

# Middleware

Ubicación:

```text
app/Http/Middleware/
```

---

# Middleware de Seguridad

```text
AuthMiddleware
RoleMiddleware
PermissionMiddleware
TenantMiddleware
TwoFactorMiddleware
```

---

# Protección de Rutas

Ejemplo:

```php
Route::middleware([
    'auth',
    'permission:tickets.view'
]);
```

---

# Tenant Validation

Toda autorización deberá validar:

```php
$user->company_id
```

contra:

```php
$model->company_id
```

---

# Branch Permissions

Nivel opcional de control:

```text
branch_id
```

---

# Caso

```text
Sucursal Barranquilla
Sucursal Cartagena
Sucursal Bogotá
```

Un usuario solo podrá acceder a su sucursal autorizada.

---

# Seguridad Avanzada

## 2FA

Roles obligatorios:

```text
owner
manager
super_admin
```

---

# Session Control

```text
IP Tracking
Device Tracking
Session Expiration
```

---

# Auditoría

Registrar:

```text
Role Assigned
Role Removed
Permission Granted
Permission Revoked
Unauthorized Access
```

---

# Eventos

```text
RoleAssigned
RoleRemoved
PermissionGranted
PermissionRevoked
UnauthorizedAccessDetected
```

---

# Jobs

```text
SyncPermissionsJob
RefreshPermissionCacheJob
AuditPermissionChangesJob
```

---

# Cache de Permisos

Redis:

```text
permissions:{user_id}
roles:{user_id}
```

---

# Seeders

```text
RoleSeeder
PermissionSeeder
RolePermissionSeeder
```

---

# Testing

## Unit Tests

```text
RoleTest
PermissionTest
PolicyTest
TenantPermissionTest
```

---

## Feature Tests

```text
UserCanAccessTicketTest
CrossTenantAccessDeniedTest
RoleAssignmentTest
PermissionMiddlewareTest
```

---

# Reglas de Negocio

## Regla 1

Todo acceso debe pasar por Policy.

---

## Regla 2

Todo acceso debe validar Tenant.

---

## Regla 3

No se permiten permisos implícitos.

---

## Regla 4

Toda modificación de permisos debe auditarse.

---

## Regla 5

Los Super Admin pueden administrar toda la plataforma.

---

## Regla 6

Los usuarios empresariales solo pueden acceder a datos de su empresa.

---

# Flujo de Autorización

```text
Usuario
   ↓
Login
   ↓
Role
   ↓
Permission
   ↓
Policy
   ↓
Tenant Validation
   ↓
Acceso Permitido
```

---

# Resultado Esperado

IAtechs Pro contará con una arquitectura de autorización Enterprise robusta, basada en roles, permisos, políticas y validaciones Multi-Tenant, garantizando seguridad, escalabilidad y control granular sobre todos los módulos de la plataforma.
