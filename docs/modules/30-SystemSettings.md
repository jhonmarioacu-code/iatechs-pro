# Module Specification

# IAtechs Pro

## Módulo: System Settings

---

# Objetivo

Centralizar toda la configuración global y empresarial de IAtechs Pro, permitiendo administrar parámetros del sistema, configuraciones operativas, seguridad, integraciones, branding y preferencias de cada empresa.

---

# Nombre Técnico

SystemSettings

---

# Tabla Principal

system_settings

---

# Dependencias

* Companies
* Users
* RolesPermissions
* Notifications
* AIAssistant
* Analytics

---

# Descripción

El módulo System Settings será el centro de configuración de toda la plataforma.

Permitirá administrar:

* Configuración general.
* Configuración empresarial.
* Seguridad.
* Integraciones.
* Notificaciones.
* IA.
* Facturación.
* Branding.
* Preferencias regionales.

---

# Categorías de Configuración

## General

```text
general
```

---

## Security

```text
security
```

---

## Notifications

```text
notifications
```

---

## AI

```text
ai
```

---

## Billing

```text
billing
```

---

## Branding

```text
branding
```

---

## Integrations

```text
integrations
```

---

## Localization

```text
localization
```

---

# Tabla system_settings

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| setting_group | string    |
| setting_key   | string    |
| setting_value | json      |
| is_encrypted  | boolean   |
| created_at    | timestamp |
| updated_at    | timestamp |

---

# Tabla api_integrations

| Campo      | Tipo      |
| ---------- | --------- |
| id         | bigint    |
| company_id | bigint    |
| provider   | string    |
| api_key    | text      |
| secret_key | text      |
| is_active  | boolean   |
| created_at | timestamp |

---

# Tabla branding_settings

| Campo           | Tipo      |
| --------------- | --------- |
| id              | bigint    |
| company_id      | bigint    |
| company_logo    | string    |
| company_favicon | string    |
| primary_color   | string    |
| secondary_color | string    |
| created_at      | timestamp |

---

# Migración Oficial System Settings

```php
Schema::create('system_settings', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->nullable()
        ->constrained('companies');

    $table->string('setting_group');

    $table->string('setting_key');

    $table->json('setting_value')
        ->nullable();

    $table->boolean('is_encrypted')
        ->default(false);

    $table->timestamps();
});
```

---

# Relaciones

## Company

```php
public function company()
{
    return $this->belongsTo(Company::class);
}
```

---

# Modelo

```text
app/Models/SystemSetting.php
```

---

# Repository

```text
app/Repositories/SystemSettingRepository.php
```

---

# Service

```text
app/Services/SystemSettingService.php
```

---

# Responsabilidades

* Gestionar configuraciones.
* Administrar integraciones.
* Configurar branding.
* Gestionar seguridad.
* Gestionar IA.
* Configurar notificaciones.
* Administrar preferencias regionales.

---

# Policy

```text
SystemSettingPolicy
```

---

# Permisos

```text
settings.view
settings.update
settings.security
settings.integrations
settings.branding
settings.billing
settings.ai
settings.admin
```

---

# Endpoints Web

```http
GET     /settings
GET     /settings/general
GET     /settings/security
GET     /settings/notifications
GET     /settings/branding
GET     /settings/integrations

PUT     /settings/update
```

---

# Endpoints API

```http
GET     /api/v1/settings
PUT     /api/v1/settings
```

---

# Configuración General

## Empresa

```text
Nombre Empresa
NIT
Dirección
Teléfono
Correo
Sitio Web
```

---

## Regional

```text
Zona Horaria
Idioma
Moneda
Formato Fecha
```

---

# Seguridad

## Opciones

```text
2FA
Política de Contraseñas
Tiempo de Sesión
Control de IP
Bloqueo de Usuarios
```

---

# Notificaciones

## Configuración

```text
SMTP
Amazon SES
WhatsApp Business
SMS
Push Notifications
```

---

# Configuración IA

## Proveedores

```text
Groq
OpenAI
Claude
Local LLM
```

---

## Opciones

```text
Modelo Predeterminado
Límite Tokens
Costo Máximo
Automatizaciones
```

---

# Branding

## Personalización

```text
Logo
Favicon
Colores Corporativos
Tema Oscuro
Tema Claro
```

---

# Integraciones

## AWS

```text
S3
SES
SNS
CloudWatch
```

---

## Pagos

```text
Stripe
PayPal
Mercado Pago
```

---

## Comunicación

```text
Twilio
Meta WhatsApp
Firebase
```

---

# Flujo de Negocio

## Actualizar Configuración

```text
Administrador
      ↓
Editar Configuración
      ↓
Validación
      ↓
Guardar
      ↓
Audit Log
```

---

# Reglas de Negocio

## Regla 1

Toda configuración pertenece a una empresa.

---

## Regla 2

Las claves sensibles deben almacenarse cifradas.

---

## Regla 3

Toda modificación debe auditarse.

---

## Regla 4

Solo administradores podrán modificar configuraciones críticas.

---

## Regla 5

Las configuraciones deben cargarse desde caché cuando sea posible.

---

## Regla 6

Toda integración debe validar conectividad antes de activarse.

---

# Auditoría

Registrar:

```text
Configuración creada
Configuración actualizada
Integración activada
Integración desactivada
Cambio de branding
Cambio de seguridad
```

---

# Eventos

```text
SettingCreated
SettingUpdated
IntegrationActivated
IntegrationDisabled
BrandingUpdated
SecurityUpdated
```

---

# Jobs

```text
ValidateIntegrationsJob
RefreshSettingsCacheJob
BackupSettingsJob
```

---

# Testing

## Unit Tests

```text
SystemSettingServiceTest
IntegrationValidationTest
BrandingConfigurationTest
```

---

## Feature Tests

```text
UpdateSettingsTest
SecuritySettingsTest
IntegrationActivationTest
BrandingUpdateTest
```

---

# KPI del Módulo

```text
Configuraciones activas
Integraciones conectadas
Cambios realizados
Errores de integración
Tiempo de respuesta configuración
```

---

# Integración con Otros Módulos

```text
Companies
Users
Notifications
AIAssistant
Analytics
AuditLogs
FileManager
Subscriptions
```

---

# Resultado Esperado

El módulo System Settings permitirá que IAtechs Pro administre centralmente toda la configuración empresarial y técnica de la plataforma, garantizando flexibilidad, seguridad, escalabilidad y personalización para cada empresa cliente del ecosistema SaaS.
