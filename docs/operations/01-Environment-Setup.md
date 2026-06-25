# IAtechs Pro

# Operations

## 01-Environment-Setup

---

# Objetivo

Definir el procedimiento oficial para preparar los entornos de IAtechs Pro garantizando consistencia, estabilidad y compatibilidad entre Development, Staging y Production.

---

# Alcance

Aplica a:

```text
Development
Testing
Staging
Production
```

---

# Stack Oficial

## Backend

```text
Laravel 12
PHP 8.4
Composer 2.x
```

---

## Frontend

```text
Blade
Vite
Tailwind CSS
Alpine.js
```

---

## Base de Datos

```text
PostgreSQL 17+
```

---

## Cache

```text
Redis 7+
```

---

## Web Server

```text
Nginx
```

---

## Queue System

```text
Laravel Horizon
```

---

## Process Manager

```text
Supervisor
```

---

## Cloud Provider

```text
AWS
```

---

# Sistemas Operativos Soportados

## Desarrollo

```text
Windows 11
Ubuntu 24.04 LTS
macOS
```

---

## Producción

```text
Ubuntu Server 24.04 LTS
```

---

# Versiones Oficiales

| Software   | Versión        |
| ---------- | -------------- |
| PHP        | 8.4            |
| Laravel    | 12             |
| PostgreSQL | 17+            |
| Redis      | 7+             |
| Nginx      | Última estable |
| Composer   | 2.x            |
| Node.js    | 22 LTS         |
| npm        | Última estable |

---

# Entorno Development

## Objetivo

Entorno utilizado por desarrolladores.

---

## Características

```text
Debug habilitado
Logs detallados
Datos de prueba
```

---

## APP_ENV

```env
APP_ENV=local
```

---

## APP_DEBUG

```env
APP_DEBUG=true
```

---

# Entorno Testing

## Objetivo

Pruebas automatizadas.

---

## APP_ENV

```env
APP_ENV=testing
```

---

## Base de Datos

```text
Base independiente
```

---

# Entorno Staging

## Objetivo

Validación antes de producción.

---

## Características

```text
Replica de producción
Sin datos reales
Pruebas QA
```

---

## APP_ENV

```env
APP_ENV=staging
```

---

## APP_DEBUG

```env
APP_DEBUG=false
```

---

# Entorno Production

## Objetivo

Operación real.

---

## APP_ENV

```env
APP_ENV=production
```

---

## APP_DEBUG

```env
APP_DEBUG=false
```

---

# Estructura Oficial

```text
IAtechs Pro

app/
bootstrap/
config/
database/
docs/
public/
resources/
routes/
storage/
tests/
```

---

# Arquitectura DDD

```text
app/Domains

Companies
Users
Customers
Tickets
Inventory
Invoices
AI
CRM
Accounting
```

---

# Multi Tenant

Modelo oficial:

```text
Shared Database

Shared Schema

Tenant Isolation by company_id
```

---

# Configuración PostgreSQL

## Driver

```env
DB_CONNECTION=pgsql
```

---

## Puerto

```env
DB_PORT=5432
```

---

# Configuración Redis

```env
REDIS_CLIENT=phpredis
```

---

## Puerto

```env
REDIS_PORT=6379
```

---

# Configuración Queue

```env
QUEUE_CONNECTION=redis
```

---

# Configuración Cache

```env
CACHE_STORE=redis
```

---

# Configuración Session

```env
SESSION_DRIVER=redis
```

---

# Configuración Horizon

```env
HORIZON_PREFIX=iatechs
```

---

# Configuración Storage

## Driver

```env
FILESYSTEM_DISK=s3
```

---

# AWS S3

Estructura:

```text
companies/

├── 1/
├── 2/
├── 3/
└── n/
```

---

# Configuración Mail

Proveedor recomendado:

```text
Amazon SES
```

---

# Configuración IA

## Variables

```env
OPENAI_API_KEY=

GEMINI_API_KEY=

OLLAMA_URL=
```

---

# Configuración Logs

Canal:

```env
LOG_CHANNEL=stack
```

---

# Configuración Seguridad

## Sanctum

```text
Laravel Sanctum
```

---

## Roles

```text
super_admin
owner
manager
technician
customer
```

---

# Variables Críticas

```env
APP_KEY=

DB_PASSWORD=

REDIS_PASSWORD=

AWS_SECRET_ACCESS_KEY=

OPENAI_API_KEY=
```

---

# Nunca Guardar en Git

```text
.env

Credenciales

Secrets

API Keys
```

---

# Herramientas de Desarrollo

## Calidad

```text
Laravel Pint
PHPStan
Pest
PHPUnit
```

---

## Testing

```text
Unit Tests
Feature Tests
Integration Tests
```

---

# Comandos Obligatorios

## Instalar Dependencias

```bash
composer install
```

---

## Generar APP_KEY

```bash
php artisan key:generate
```

---

## Ejecutar Migraciones

```bash
php artisan migrate
```

---

## Ejecutar Seeders

```bash
php artisan db:seed
```

---

## Ejecutar Tests

```bash
php artisan test
```

---

# Health Check

Endpoint oficial:

```text
/health
```

---

# Debe Validar

```text
Application

Database

Redis

Queues

Storage
```

---

# Checklist Inicial

```text
PHP instalado

Composer instalado

PostgreSQL instalado

Redis instalado

Laravel configurado

Migraciones ejecutadas

Seeders ejecutados

Tests exitosos
```

---

# Resultado Esperado

Todo entorno de IAtechs Pro deberá ser reproducible, seguro, consistente y compatible con la arquitectura Enterprise SaaS Multi-Tenant definida por la plataforma.
