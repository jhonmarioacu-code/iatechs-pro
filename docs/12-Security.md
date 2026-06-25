# Security

# IAtechs Pro

## Política de Seguridad Empresarial

---

# Objetivo

Garantizar la confidencialidad, integridad, disponibilidad y trazabilidad de toda la información administrada por IAtechs Pro.

---

# Principios de Seguridad

## Confidencialidad

Solo usuarios autorizados podrán acceder a la información.

---

## Integridad

Los datos no podrán ser alterados sin autorización.

---

## Disponibilidad

La plataforma deberá permanecer accesible y operativa.

---

## Trazabilidad

Toda acción crítica deberá quedar registrada.

---

# Arquitectura de Seguridad

```text
Usuario
   │
Autenticación
   │
Autorización
   │
Middleware
   │
Policies
   │
Services
   │
Database
```

---

# Autenticación

## Tecnología

```text
Laravel Sanctum
```

---

## Métodos Soportados

### Email + Contraseña

### Inicio de sesión corporativo (Futuro)

* Google
* Microsoft
* GitHub

---

# Política de Contraseñas

## Longitud mínima

```text
12 caracteres
```

---

## Requisitos

Debe contener:

* Mayúsculas
* Minúsculas
* Números
* Caracteres especiales

---

## Ejemplo válido

```text
IAtechs@2026Secure
```

---

# Almacenamiento de Contraseñas

Nunca almacenar texto plano.

Método:

```text
Hash Bcrypt
```

o

```text
Argon2id
```

(Recomendado para producción)

---

# Recuperación de Contraseña

Proceso:

```text
Solicitud
   ↓
Token temporal
   ↓
Correo
   ↓
Cambio de contraseña
```

---

# Gestión de Sesiones

## Expiración

```text
120 minutos
```

de inactividad.

---

## Cierre de sesión

Manual o automático.

---

# Control de Acceso

## Modelo

```text
RBAC
Role Based Access Control
```

---

## Librería

```text
Spatie Permission
```

---

# Roles

```text
Super Admin
Company Owner
Administrator
Technical Manager
Receptionist
Inventory Manager
Accountant
Technician
Customer
```

---

# Permisos

Toda operación deberá validarse mediante:

```php
Gate
Policy
Permission
```

---

# Seguridad Multiempresa

## Regla Principal

Toda entidad empresarial deberá contener:

```text
company_id
```

---

# Aislamiento

Un usuario nunca podrá consultar datos de otra empresa.

---

# Policies Laravel

Obligatorias para:

```text
Users
Customers
Devices
Tickets
Quotes
Repairs
Invoices
Products
Reports
```

---

# Middleware de Seguridad

## Autenticación

```text
auth
```

---

## Empresa activa

```text
company.active
```

---

## Suscripción activa

```text
subscription.active
```

---

## Rol

```text
role
```

---

## Permiso

```text
permission
```

---

# Protección contra Ataques

## CSRF

Protección nativa Laravel.

---

## XSS

Escapado automático Blade.

Validación de entradas.

---

## SQL Injection

Uso obligatorio de:

```text
Eloquent ORM
Query Builder
```

Prohibido construir consultas SQL concatenadas.

---

## Mass Assignment

Todos los modelos deberán definir:

```php
$fillable
```

o

```php
$guarded
```

---

# Rate Limiting

## API Pública

```text
60 requests/minuto
```

---

## Usuarios autenticados

```text
120 requests/minuto
```

---

## Endpoints IA

```text
30 requests/minuto
```

---

# Registro de Auditoría

## Acciones auditables

```text
Login
Logout
Creación
Actualización
Eliminación
Aprobaciones
Facturación
Pagos
Roles
Permisos
```

---

# Información Auditada

```text
Usuario
Fecha
Hora
IP
Acción
Módulo
```

---

# Logs

## Laravel

```text
storage/logs
```

---

## Nginx

```text
/var/log/nginx
```

---

## Sistema

```text
journalctl
```

---

# Cifrado de Datos

## Laravel Encryption

```text
AES-256-CBC
```

---

# Datos Sensibles

Cifrar:

```text
Tokens
API Keys
Credenciales Externas
```

---

# Gestión de Archivos

## Validaciones

Tipos permitidos:

```text
jpg
png
pdf
docx
xlsx
```

---

## Tamaño Máximo

```text
20 MB
```

---

# Almacenamiento

## Desarrollo

```text
storage/app
```

---

## Producción

```text
Amazon S3
```

---

# Seguridad de Infraestructura

## Sistema Operativo

```text
Ubuntu Server 24.04 LTS
```

---

# Firewall

## UFW

Puertos permitidos:

```text
22
80
443
```

---

# SSH

Configuración obligatoria:

```text
PermitRootLogin no
PasswordAuthentication no
```

---

# Certificados SSL

## Producción

```text
Let's Encrypt
```

Renovación automática.

---

# Base de Datos

## PostgreSQL

Acceso:

```text
localhost
```

únicamente.

---

# Usuario dedicado

```text
iatechs_user
```

---

# Backups

## Base de Datos

Frecuencia:

```text
Diaria
```

---

## Archivos

Frecuencia:

```text
Diaria
```

---

## Retención

```text
30 días
```

---

# Recuperación ante Desastres

## Objetivos

### RPO

```text
24 horas
```

Máxima pérdida aceptable.

---

### RTO

```text
4 horas
```

Tiempo máximo de recuperación.

---

# Seguridad IA

## Restricciones

La IA no podrá:

* Eliminar registros.
* Modificar datos directamente.
* Aprobar transacciones.

---

# La IA podrá

* Recomendar.
* Analizar.
* Resumir.
* Asistir.

---

# Cumplimiento OWASP

La plataforma deberá cumplir:

```text
OWASP Top 10
```

Controles mínimos:

* Control de acceso.
* Criptografía.
* Gestión de sesiones.
* Validación de entradas.
* Registro de eventos.
* Configuración segura.

---

# Monitoreo

## Laravel Pulse

Métricas:

```text
Usuarios
Errores
Rendimiento
Colas
```

---

## Horizon

Monitoreo de:

```text
Jobs
Queues
Workers
```

---

# Seguridad del Desarrollo

## Repositorio

```text
GitHub
```

---

# Reglas

Nunca subir:

```text
.env
storage/
vendor/
```

---

# Archivos protegidos

```text
.env
.env.production
.env.staging
```

---

# Resultado Esperado

IAtechs Pro deberá operar bajo estándares de seguridad empresariales modernos, protegiendo la información de múltiples empresas, garantizando trazabilidad, cumplimiento de buenas prácticas OWASP, aislamiento multiempresa y capacidad de recuperación ante incidentes.
