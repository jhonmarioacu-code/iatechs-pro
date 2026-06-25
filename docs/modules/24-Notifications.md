# Module Specification

# IAtechs Pro

## Módulo: Notifications

---

# Objetivo

Centralizar la gestión, envío, seguimiento y auditoría de todas las notificaciones generadas por IAtechs Pro.

---

# Nombre Técnico

Notifications

---

# Tabla Principal

notifications

---

# Dependencias

* Companies
* Users
* Customers
* Tickets
* Repairs
* Invoices
* Payments
* WorkOrders
* ServiceContracts

---

# Descripción

Este módulo permite enviar comunicaciones automáticas y manuales a clientes, técnicos y administradores mediante múltiples canales.

---

# Canales Soportados

## Email

```text
email
```

---

## WhatsApp

```text
whatsapp
```

---

## SMS

```text
sms
```

---

## Push

```text
push
```

---

## InApp

```text
in_app
```

---

# Estados

## Pending

```text
pending
```

---

## Processing

```text
processing
```

---

## Sent

```text
sent
```

---

## Delivered

```text
delivered
```

---

## Failed

```text
failed
```

---

## Read

```text
read
```

---

# Tipos de Notificación

## Ticket Created

```text
ticket_created
```

---

## Ticket Updated

```text
ticket_updated
```

---

## Quote Ready

```text
quote_ready
```

---

## Repair Completed

```text
repair_completed
```

---

## Invoice Issued

```text
invoice_issued
```

---

## Payment Received

```text
payment_received
```

---

## Warranty Expiring

```text
warranty_expiring
```

---

## Contract Expiring

```text
contract_expiring
```

---

## Work Order Assigned

```text
work_order_assigned
```

---

# Tabla notifications

| Campo             | Tipo      |
| ----------------- | --------- |
| id                | bigint    |
| company_id        | bigint    |
| user_id           | bigint    |
| customer_id       | bigint    |
| channel           | string    |
| notification_type | string    |
| subject           | string    |
| message           | text      |
| status            | string    |
| sent_at           | timestamp |
| delivered_at      | timestamp |
| read_at           | timestamp |
| metadata          | json      |
| created_at        | timestamp |
| updated_at        | timestamp |

---

# Tabla notification_templates

| Campo      | Tipo      |
| ---------- | --------- |
| id         | bigint    |
| company_id | bigint    |
| name       | string    |
| channel    | string    |
| subject    | string    |
| content    | text      |
| is_active  | boolean   |
| created_at | timestamp |

---

# Migración Oficial Notifications

```php
Schema::create('notifications', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users');

    $table->foreignId('customer_id')
        ->nullable()
        ->constrained('customers');

    $table->string('channel');

    $table->string('notification_type');

    $table->string('subject')
        ->nullable();

    $table->longText('message');

    $table->enum('status',[
        'pending',
        'processing',
        'sent',
        'delivered',
        'failed',
        'read'
    ])->default('pending');

    $table->timestamp('sent_at')
        ->nullable();

    $table->timestamp('delivered_at')
        ->nullable();

    $table->timestamp('read_at')
        ->nullable();

    $table->json('metadata')
        ->nullable();

    $table->timestamps();
});
```

---

# Relaciones

## User

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

---

## Customer

```php
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

---

# Modelo

```text
app/Models/Notification.php
```

---

# Repository

```text
app/Repositories/NotificationRepository.php
```

---

# Service

```text
app/Services/NotificationService.php
```

---

# Responsabilidades

* Enviar correos electrónicos.
* Enviar WhatsApp.
* Enviar SMS.
* Enviar Push Notifications.
* Gestionar plantillas.
* Registrar entregas.
* Auditar comunicaciones.

---

# Policy

```text
NotificationPolicy
```

---

# Permisos

```text
notifications.view
notifications.create
notifications.send
notifications.retry
notifications.delete
notifications.export
notifications.templates
```

---

# Endpoints Web

```http
GET     /notifications
GET     /notifications/templates
POST    /notifications/send
GET     /notifications/{id}
POST    /notifications/{id}/retry
```

---

# Endpoints API

```http
GET     /api/v1/notifications
POST    /api/v1/notifications
GET     /api/v1/notifications/{id}
POST    /api/v1/notifications/send
```

---

# Integraciones Externas

## Email

```text
SMTP
Amazon SES
Mailgun
Resend
```

---

## WhatsApp

```text
Meta WhatsApp Business API
Twilio WhatsApp
```

---

## SMS

```text
Twilio
AWS SNS
```

---

## Push

```text
Firebase Cloud Messaging
OneSignal
```

---

# Flujo de Negocio

## Ticket Creado

```text
Ticket
   ↓
Evento
   ↓
Notification Job
   ↓
Canal Seleccionado
   ↓
Cliente
```

---

## Factura Emitida

```text
Invoice
   ↓
PDF
   ↓
Email
   ↓
Cliente
```

---

## Orden de Trabajo

```text
Asignación
    ↓
Push
    ↓
Técnico
```

---

# Reglas de Negocio

## Regla 1

Toda notificación debe registrarse.

---

## Regla 2

Los errores de envío deben permitir reintentos.

---

## Regla 3

Las plantillas son configurables por empresa.

---

## Regla 4

Los canales pueden activarse o desactivarse.

---

## Regla 5

Toda comunicación debe quedar auditada.

---

## Regla 6

Los envíos masivos deben ejecutarse mediante colas.

---

# Auditoría

Registrar:

```text
Notificación creada
Notificación enviada
Notificación entregada
Notificación leída
Notificación fallida
Reintento ejecutado
```

---

# Eventos

```text
NotificationCreated
NotificationSent
NotificationDelivered
NotificationRead
NotificationFailed
```

---

# Jobs

```text
SendEmailJob
SendWhatsAppJob
SendSmsJob
SendPushNotificationJob
RetryFailedNotificationJob
```

---

# Testing

## Unit Tests

```text
NotificationServiceTest
EmailChannelTest
WhatsAppChannelTest
```

---

## Feature Tests

```text
SendNotificationTest
RetryNotificationTest
PushNotificationTest
TemplateNotificationTest
```

---

# KPI del Módulo

* Notificaciones enviadas.
* Tasa de entrega.
* Tasa de apertura.
* Errores de envío.
* Tiempo promedio de entrega.
* WhatsApp enviados.
* Correos enviados.

---

# Integración con Otros Módulos

```text
Tickets
Diagnostics
Repairs
Invoices
Payments
Warranties
ServiceContracts
WorkOrders
TechnicianSchedules
AuditLogs
Analytics
```

---

# Resultado Esperado

El módulo Notifications permitirá que IAtechs Pro automatice todas las comunicaciones internas y externas, mejorando la experiencia del cliente, aumentando la productividad operativa y garantizando trazabilidad completa de cada mensaje enviado.
