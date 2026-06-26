# Frontend Portal UX System

Fecha: 2026-06-26

## Objetivo

Definir el patron oficial de interfaz para IAtechs Pro en portales por rol sin romper el dominio ni la arquitectura enterprise.

## Alcance

- Blade + Tailwind + Alpine.
- Portales: admin, company, technician, customer.
- Componentes compartidos: `portal-shell`, `sidebar`, `topbar`, `notification-center`, `floating-ai`.

## Principios de diseno

1. Rol primero: cada rol tiene identidad visual y foco operativo.
2. Contexto rapido: KPIs y paneles de decision visibles al inicio.
3. Interaccion ligera: tabs y paneles con Alpine sin sobrecargar JS.
4. Consistencia: mismo sistema de superficies, tablas y estados.
5. Seguridad UX: sin exponer acciones fuera de permisos/plan.

## Tokens y temas

- Tema base via variables CSS en `resources/css/app.css`.
- Tema por rol via `data-portal-theme` en `portal-shell`.
- Modo oscuro conservado por `localStorage`.

## Estructura de pantalla por rol

1. Header contextual con portal + meta de estado.
2. Grid KPI como resumen operativo.
3. Tarjetas de trabajo con tablas y acciones.
4. IA y notificaciones en paneles laterales.

## Componentes oficiales

- `x-portal-shell`: compone layout y menu por rol.
- `x-sidebar`: navegacion autorizada por permisos.
- `x-topbar`: busqueda, usuario, seguridad y salida.
- `x-floating-ai`: asistente con conversaciones por portal.
- `x-notification-center`: eventos operativos de alto valor.

## Reglas de extension

1. Todo nuevo modulo de portal debe usar `surface-card` y `data-table`.
2. No duplicar layouts por dominio; extender componentes base.
3. Mantener textos criticos usados por testing funcional.
4. Toda mejora visual debe ser responsive desktop/mobile.
5. Si se agrega un rol nuevo, definir tema y menu en `portal-shell`.

## QA minimo para cambios frontend

1. `npm run build`
2. `composer analyse`
3. Smoke por rol:
   - Login y redirect correcto.
   - Dashboard render.
   - Navegacion sidebar.
   - Apertura de notificaciones.
   - Apertura de IA (si aplica permiso).
