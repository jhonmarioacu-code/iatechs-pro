# Module Specification

# IAtechs Pro

## Módulo: Audit Logs

---

# Objetivo

Registrar, almacenar y consultar todas las acciones ejecutadas dentro de IAtechs Pro para garantizar seguridad, trazabilidad, cumplimiento normativo y análisis forense.

---

# Nombre Técnico

AuditLogs

---

# Tabla Principal

audit_logs

---

# Dependencias

* Companies
* Users
* Roles
* Permissions

---

# Descripción

Este módulo registra automáticamente cualquier acción importante realizada dentro del sistema.

Permite identificar:

* Quién realizó la acción.
* Cuándo ocurrió.
* Desde qué IP.
* Qué información fue modificada.
* Qué cambios se realizaron.

---

# Tipos de Eventos

## Create

```text
create
```

Creación de registros.

---

## Update

```text
update
```

Modificación de registros.

---

## Delete

```text
delete
```

Eliminación de registros.

---

## Login

```text
login
```

Inicio de sesión.

---

## Logout

```text
logout
```

Cierre de sesión.

---

## Export

```text
export
```

Exportación de información.

---

## Permission Change

```text
permission_change
```

Cambio de permisos.

---

## Role Change

```text
role_change
```

Cambio de roles.

---

## System Event

```text
system_event
```

Evento interno del sistema.

---

# Tabla audit_logs

| Campo       | Tipo      |
| ----------- | --------- |
| id          | bigint    |
| company_id  | bigint    |
| user_id     | bigint    |
| event_type  | string    |
| module      | string    |
| entity_type | string    |
| entity_id   | bigint    |
| old_values  | json      |
| new_values  | json      |
| ip_address  | string    |
| user_agent  | text      |
| description | text      |
| created_at  | timestamp |

---

# Tabla audit_sessions

| Campo      | Tipo     |
| ---------- | -------- |
| id         | bigint   |
| user_id    | bigint   |
| login_at   | datetime |
| logout_at  | datetime |
| ip_address | string   |
| user_agent | text     |
| status     | string   |

---

# Tabla audit_exports

| Campo       | Tipo     |
| ----------- | -------- |
| id          | bigint   |
| user_id     | bigint   |
| module      | string   |
| export_type | string   |
| exported_at | datetime |
| file_name   | string   |

---

# Migración Oficial Audit Logs

```php
Schema::create('audit_logs', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->nullable()
        ->constrained('companies');

    $table->foreignId('user_id')
        ->nullable()
        ->constrained('users');

    $table->string('event_type');

    $table->string('module');

    $table->string('entity_type')
        ->nullable();

    $table->unsignedBigInteger('entity_id')
        ->nullable();

    $table->json('old_values')
        ->nullable();

    $table->json('new_values')
        ->nullable();

    $table->string('ip_address')
        ->nullable();

    $table->text('user_agent')
        ->nullable();

    $table->text('description')
        ->nullable();

    $table->timestamp('created_at')
        ->useCurrent();
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
app/Models/AuditLog.php
```

---

# Repository

```text
app/Repositories/AuditLogRepository.php
```

---

# Service

```text
app/Services/AuditLogService.php
```

---

# Responsabilidades

* Registrar eventos.
* Registrar cambios.
* Registrar accesos.
* Registrar exportaciones.
* Monitorear actividad.
* Permitir auditorías.
* Proveer trazabilidad completa.

---

# Policy

```text
AuditLogPolicy
```

---

# Permisos

```text
audit_logs.view
audit_logs.export
audit_logs.sessions
audit_logs.security
audit_logs.admin
```

---

# Endpoints Web

```http
GET     /audit-logs
GET     /audit-logs/{id}
GET     /audit-logs/sessions
GET     /audit-logs/exports
POST    /audit-logs/export
```

---

# Endpoints API

```http
GET     /api/v1/audit-logs
GET     /api/v1/audit-logs/{id}
GET     /api/v1/audit-logs/sessions
```

---

# Eventos Registrados

## Seguridad

```text
Login
Logout
Password Reset
Failed Login
2FA Enabled
2FA Disabled
```

---

## Operación

```text
Ticket Created
Repair Updated
Invoice Generated
Payment Received
Work Order Completed
```

---

## Administración

```text
User Created
Role Assigned
Permission Updated
Company Modified
Subscription Updated
```

---

# Flujo de Negocio

## Modificación de Registro

```text
Usuario
   ↓
Actualiza Registro
   ↓
Audit Service
   ↓
Guardar Cambios
   ↓
Audit Log
```

---

## Inicio de Sesión

```text
Usuario
   ↓
Login
   ↓
Registrar IP
   ↓
Registrar Dispositivo
   ↓
Audit Session
```

---

# Reglas de Negocio

## Regla 1

Ningún registro de auditoría podrá ser modificado.

---

## Regla 2

Los registros de auditoría no podrán eliminarse desde la aplicación.

---

## Regla 3

Toda exportación deberá quedar auditada.

---

## Regla 4

Los cambios de permisos deben registrarse.

---

## Regla 5

Los eventos críticos generarán alertas.

---

## Regla 6

La retención mínima será de 5 años.

---

# Auditoría del Módulo

Registrar:

```text
Log creado
Sesión registrada
Exportación registrada
Alerta generada
Evento crítico detectado
```

---

# Eventos

```text
AuditLogCreated
UserSessionStarted
UserSessionEnded
CriticalEventDetected
AuditExportGenerated
```

---

# Jobs

```text
CleanupOldAuditLogsJob
GenerateSecurityReportJob
DetectSuspiciousActivityJob
ArchiveAuditLogsJob
```

---

# Testing

## Unit Tests

```text
AuditLogServiceTest
AuditSessionTest
AuditExportTest
```

---

## Feature Tests

```text
CreateAuditLogTest
UserLoginAuditTest
ExportAuditLogsTest
SecurityEventTest
```

---

# KPI del Módulo

```text
Eventos registrados
Usuarios activos
Intentos fallidos de acceso
Exportaciones realizadas
Eventos críticos detectados
Sesiones activas
```

---

# Integración con Otros Módulos

```text
Users
RolesPermissions
Companies
Notifications
Reports
Analytics
Security
```

---

# Resultado Esperado

El módulo Audit Logs permitirá que IAtechs Pro mantenga trazabilidad completa de todas las acciones realizadas dentro de la plataforma, fortaleciendo la seguridad, el cumplimiento normativo y la capacidad de auditoría empresarial.
