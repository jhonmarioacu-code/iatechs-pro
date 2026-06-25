# IAtechs Pro

# Operations

## 09-RBAC-Portal-Matrix.md

Fecha: 2026-06-25

## Objetivo

Normalizar el control de acceso por portal, rol, permisos y rutas criticas para mantener consistencia con arquitectura SaaS multi-tenant.

## Matriz Portal -> Rol -> Capacidades

### Admin Portal (`/portal/admin`)

- Rol primario: `super_admin`
- Alcance: global SaaS (sin aislamiento por tenant operativo para catalogo global).
- Capacidades:
  - Gestion de empresas, usuarios, planes, suscripciones.
  - Observabilidad y operaciones de plataforma.

### Company Portal (`/portal/company`)

- Roles: `owner`, `administrator`, `manager`, `receptionist`, `warehouse`, `accountant`.
- Alcance: solo `company_id` del usuario autenticado.
- Capacidades:
  - Gestion operativa de tickets, clientes, personal, sucursales y modulos de negocio segun permisos.

### Technician Portal (`/portal/technician`)

- Rol: `technician`.
- Alcance: solo su `company_id` y recursos asignados al tecnico.
- Capacidades:
  - Tomar ticket disponible, diagnostico, cotizacion, reparacion, cierre.

### Customer Portal (`/portal/customer`)

- Rol: `customer`.
- Alcance: solo sus recursos de cliente dentro de su `company_id`.
- Capacidades:
  - Ver tickets/facturas, aprobar/rechazar cotizacion, pagar factura, descargar comprobante.

## Matriz de Permisos Base por Rol

Definida en `database/seeders/RolePermissionSeeder.php`.

- `super_admin`: todos los permisos (`Permission::all()`).
- `owner`: gestion integral de operacion (usuarios, sucursales, tickets, diagnosticos, cotizaciones, reparaciones, finanzas, inventario, reportes).
- `administrator`: gestion administrativa/operativa sin privilegios globales.
- `manager`: supervision de operacion tecnica y reportes.
- `receptionist`: recepcion (clientes, equipos, tickets, visualizacion de cotizaciones).
- `warehouse`: inventario, proveedores, compras operativas.
- `accountant`: facturacion, pagos, reportes.
- `technician`: flujo tecnico completo de ticket/diagnostico/cotizacion/reparacion.
- `customer`: permisos `customer.portal.*`.

## Rutas Criticas y Permisos Esperados

### Web Portals

- `/portal/admin/*` -> `portal.access:admin` + rol `super_admin`.
- `/portal/company/*` -> `portal.access:company` + permisos por accion.
- `/portal/technician/*` -> `portal.access:technician` + permisos `tickets/diagnostics/quotes/repairs`.
- `/portal/customer/*` -> `portal.access:customer` + permisos `customer.portal.*`.

### API v1 (ejemplos criticos)

- `/api/v1/tickets/*` -> `tickets.*` segun policy.
- `/api/v1/diagnostics/*` -> `diagnostics.*` segun policy.
- `/api/v1/repairs/*` -> `repairs.*` segun policy.
- `/api/v1/users/*` -> `users.*` segun policy.

## Reglas Multi-Tenant Obligatorias

1. Toda entidad operativa usa `company_id`.
2. Todo acceso se limita por policy y/o scope tenant.
3. No se permite asignacion cross-company de tecnicos.
4. Cliente solo puede operar recursos propios dentro de su empresa.

## Comandos de Sincronizacion Recomendados (Produccion)

```bash
php artisan db:seed --class=PermissionSeeder --force
php artisan db:seed --class=RoleSeeder --force
php artisan db:seed --class=RolePermissionSeeder --force
php artisan db:seed --class=SuperAdminPermissionSeeder --force
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
```

## Checklist de Validacion

1. Usuario `super_admin` entra a `/portal/admin`.
2. Usuario `owner` entra a `/portal/company` y no entra a `/portal/admin`.
3. Usuario `technician` entra a `/portal/technician` y no entra a `/portal/company`.
4. Usuario `customer` solo accede a sus tickets/facturas y cotizaciones propias.
5. API rechaza operaciones sin permiso (`403`) y sin autenticacion (`401`).
