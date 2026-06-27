# Portal Route Permission Matrix

Fecha: 2026-06-26

## Objetivo

Centralizar la fuente de verdad de acceso por portal y modulo para alinear:

- Rol -> acceso a portal.
- Permiso -> acceso a modulo.
- Ruta -> entrada de navegacion y enforcement.
- Estado de modulo -> habilitado/deshabilitado.

## Fuente unica de verdad

La definicion oficial vive en:

- `config/portal_matrix.php`

Aplicada en:

- `app/Support/PortalMatrix.php`
- `app/Http/Middleware/EnsurePortalAccess.php`
- `app/Http/Middleware/EnsurePortalModuleAccess.php`
- `resources/views/components/portal-shell.blade.php`
- `resources/views/components/sidebar.blade.php`
- `routes/web.php`

## Reglas de enforcement

1. `portal.access` valida rol contra portal (super admin bypass).
2. `portal.module` valida `portal + module` contra matriz y permiso RBAC.
3. `plan.module` valida plan para modulos del portal company.
4. La UI del sidebar se renderiza desde la misma matriz para evitar drift.

## Matriz por portal

### Admin

- Roles: solo `super_admin` (por bypass global).
- Modulos: `dashboard`, `dashboards`, `customers`, `crm`, `marketplace`, `service-desk`, `inventory`, `accounting`, `knowledge-base`, `ai-assistant`, `reports`, `observability`, `operations`, `settings`.

### Company

- Roles: `owner`, `administrator`, `manager`, `receptionist`, `warehouse`, `accountant`.
- Modulos: `dashboard`, `customers`, `devices`, `tickets`, `ai-assistant`, `products`, `invoices`, `payments`, `analytics`, `settings`.
- Restriccion de plan: aplica para company (inventario, reportes, ai y modulos limitados por plan).

### Technician

- Roles: `technician`.
- Modulos: `dashboard`, `tickets`, `diagnostics`, `repairs`, `ai-assistant`, `work-orders`, `assigned-inventory`.

### Customer

- Roles: `customer`.
- Modulos: `dashboard`, `tickets`, `invoices`, `marketplace`.

## Nota de arquitectura

Para mantener consistencia Enterprise SaaS:

- Nuevos modulos deben registrarse primero en `config/portal_matrix.php`.
- Luego se conectan rutas/controladores/policies.
- No se permite menu hardcodeado fuera de la matriz.

