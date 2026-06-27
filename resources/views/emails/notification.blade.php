<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>{{ $notification->subject ?? $notification->title }}</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #111827;">
    <h2 style="margin: 0 0 12px;">{{ $notification->title }}</h2>
    <p style="margin: 0 0 16px;">{{ $notification->message }}</p>

    @if(!empty($notification->data) && is_array($notification->data))
        <hr style="margin: 16px 0; border: 0; border-top: 1px solid #e5e7eb;">
        <p style="margin: 0 0 8px; font-weight: 600;">Datos adicionales</p>
        <pre style="white-space: pre-wrap; background: #f9fafb; padding: 12px; border-radius: 6px;">{{ json_encode($notification->data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    @endif

    <p style="margin-top: 20px; font-size: 12px; color: #6b7280;">
        IAtechs Pro - Notificacion transaccional automatica.
    </p>
</body>
</html>
