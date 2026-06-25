# IAtechs Pro Audit

Fecha: 2026-06-25

## Alcance

- Roles y permisos por portal (`super_admin`, `company`, `technician`, `customer`).
- Flujo operativo critico (`ticket -> diagnostico -> cotizacion -> reparacion -> cierre`).
- Cumplimiento DDD operativo (`Controller`, `Service`, `Repository`, `Policy`, `Request`, `Resource`).
- Multi-tenant (`company_id`) en consultas, creacion y autorizacion.

## Brechas Criticas Detectadas y Corregidas

1. `Quote` sin aislamiento tenant por trait/scope.
- Riesgo: lectura/escritura cross-tenant en consultas no protegidas por policy.
- Correccion: agregado `BelongsToCompany`.
- Archivo: `app/Domains/Quotes/Models/Quote.php`.

2. `Notification` sin aislamiento tenant por trait/scope.
- Riesgo: consultas globales de notificaciones.
- Correccion: agregado `BelongsToCompany`.
- Archivo: `app/Domains/Notifications/Models/Notification.php`.

3. `QuoteController` sin enforcement de policies.
- Riesgo: operaciones sin `authorize(...)` directo en controlador.
- Correccion: `authorize` agregado en `index/store/show/update/destroy/approve/reject/cancel`.
- Archivo: `app/Domains/Quotes/Controllers/QuoteController.php`.

4. `NotificationController` sin enforcement de policies.
- Riesgo: lectura/edicion de notificaciones sin control uniforme.
- Correccion: `authorize` agregado en `index/store/show/update/markAsRead`.
- Archivo: `app/Domains/Notifications/Controllers/NotificationController.php`.

5. `NotificationPolicy` con reglas incompletas.
- Riesgo: firmas de metodos sin recurso y permisos parciales.
- Correccion: reglas completas por permiso + `company_id` para `view/update/delete/markAsRead`.
- Archivo: `app/Domains/Notifications/Policies/NotificationPolicy.php`.

6. `QuotePolicy` no registrada en `AuthServiceProvider`.
- Riesgo: policy no aplicada automaticamente por Gate.
- Correccion: registro de `Quote::class => QuotePolicy::class`.
- Archivo: `app/Providers/AuthServiceProvider.php`.

7. Asignacion de tecnico sin restriccion de empresa en controllers API.
- Riesgo: asignar tecnico de otra empresa.
- Correccion: `Rule::exists(...)->where('company_id', ...)` en ticket y repair.
- Archivos:
  - `app/Domains/Tickets/Controllers/TicketController.php`
  - `app/Domains/Repairs/Controllers/RepairController.php`

8. Flujo cliente incompleto para aprobacion/rechazo de cotizacion.
- Riesgo: flujo critico truncado entre diagnostico y reparacion.
- Correccion:
  - Cliente puede aprobar/rechazar cotizacion pendiente.
  - `approve -> ticket APPROVED`.
  - `reject -> ticket WAITING_QUOTE`.
- Archivos:
  - `app/Http/Controllers/CustomerPortalController.php`
  - `routes/web.php`
  - `resources/views/portals/customer/ticket.blade.php`

9. Catalogo de permisos incompleto para quotes/notifications.
- Riesgo: politicas referenciando permisos no sembrados.
- Correccion: agregado de permisos faltantes.
- Archivo: `database/seeders/PermissionSeeder.php`.

10. Cargador de rutas administrativas desacoplado de `routes/web.php`.
- Riesgo: endpoints de `routes/admin/*` no registrados en runtime (arquitectura desincronizada).
- Correccion: agregado `Route::prefix('admin')->group(base_path('routes/admin.php'));`.
- Archivo: `routes/web.php`.

11. Middleware inconsistente en `routes/admin/*`.
- Riesgo: rutas admin solo con `auth` sin `tenant` ni `portal.access:admin`.
- Correccion: estandarizacion de middleware en todos los modulos admin.
- Base estandar: `auth`, `tenant`, `portal.access:admin`.
- Endpoints globales criticos (`roles`, `permissions`, `plans`): adicional `role:super_admin`.
- Archivos: `routes/admin/*.php`.

## Estado por Punto Solicitado

1. Roles y permisos por portal: **Alineado** en portales y rutas admin con middleware estandar.
2. Flujo critico por rol: **Alineado** con aprobacion/rechazo de cotizacion por cliente.
3. Cumplimiento DDD por modulo: **Alineado en capas base**; estructura validada por pruebas de arquitectura.
4. Multi-tenant (`company_id`): **Alineado en dominios criticos** (`tickets/diagnostics/quotes/repairs/notifications`).
5. Brechas criticas: **Corregidas en esta iteracion**.

## Validacion

- Suite completa: `47 passed (287 assertions)`.
- Pruebas reforzadas en flujo cliente de cotizaciones y aislamiento.

## Pendientes No Criticos (Siguiente Iteracion)

1. Completar matriz formal `rol -> permiso -> ruta` para todos los endpoints `routes/admin/*` y aplicar middleware de permiso por accion (`permission:*`) donde aplique.
2. Reducir logica orquestadora en `app/Http/Controllers/*PortalController.php` moviendo reglas a `Services/Actions` de dominio.
3. Ejecutar seeders de permisos/roles en produccion despues de despliegue:
   - `php artisan db:seed --class=PermissionSeeder --force`
   - `php artisan db:seed --class=RoleSeeder --force`
   - `php artisan db:seed --class=RolePermissionSeeder --force`
   - `php artisan db:seed --class=SuperAdminPermissionSeeder --force`
