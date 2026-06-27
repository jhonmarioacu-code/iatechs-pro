# Realtime Broadcast Runbook

Fecha: 2026-06-26  
Estado: Oficial

## Objetivo

Asegurar que IAtechsPro tenga realtime operativo con aislamiento multi-tenant,
autorizacion por canal y fallback de reconexion para UI.

## Alcance actual

1. Configuracion de broadcasting en `config/broadcasting.php`.
2. Autorizacion de canales privados en `routes/channels.php`.
3. Evento de dominio broadcast para notificaciones:
   `App\Domains\Notifications\Events\NotificationStreamed`.
4. Suscripcion frontend via Echo (Reverb/Pusher) con reconexion y polling de respaldo.
5. Canal por tenant y canal por usuario:
   - `private-company.{company_id}.notifications`
   - `private-user.{user_id}.notifications`

## Variables de entorno

### Backend (server)

- `BROADCAST_CONNECTION`
- `REVERB_APP_ID`
- `REVERB_APP_KEY`
- `REVERB_APP_SECRET`
- `REVERB_HOST`
- `REVERB_PORT`
- `REVERB_SCHEME`

### Frontend (Vite)

- `VITE_BROADCAST_CONNECTION`
- `VITE_REVERB_APP_KEY`
- `VITE_REVERB_HOST`
- `VITE_REVERB_PORT`
- `VITE_REVERB_SCHEME`
- `VITE_PUSHER_APP_KEY`
- `VITE_PUSHER_HOST`
- `VITE_PUSHER_PORT`
- `VITE_PUSHER_SCHEME`
- `VITE_PUSHER_APP_CLUSTER`

## Flujo operativo

1. La API crea o actualiza una notificacion en dominio Notifications.
2. `NotificationService` emite `NotificationStreamed`.
3. El evento se publica en los canales privados autorizados.
4. El frontend consume el evento y sincroniza panel de notificaciones.
5. Si el socket cae, la UI conserva fallback con polling.

## Seguridad

1. Canal company: solo usuarios de la misma `company_id`.
2. Canal user: solo el usuario propietario del canal.
3. El endpoint `/broadcasting/auth` requiere sesion autenticada.

## Comandos de validacion

```bash
php artisan iatechs:gate-release
php artisan test tests/Feature/Security/RealtimeBroadcastAuthorizationTest.php
php artisan test tests/Feature/Security/NotificationsRealtimeBroadcastTest.php
npm run build
```

## Diagnostico rapido

1. Si no llegan eventos:
   - validar `BROADCAST_CONNECTION`.
   - validar credenciales Reverb/Pusher.
   - validar queue worker y Redis.
2. Si falla auth de canal:
   - revisar sesion web activa.
   - revisar `routes/channels.php`.
   - revisar `company_id` del usuario.
3. Si la UI no refleja cambios:
   - validar `VITE_*` en build.
   - revisar consola navegador para estado de Echo.
   - verificar fallback de polling en panel de notificaciones.

