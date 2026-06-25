# IAtechs Pro

# Development Standards

## 09-Security-Standards

---

# Objetivo

Definir los estándares oficiales de seguridad de IAtechs Pro para garantizar la protección de datos, aislamiento Multi-Tenant, control de acceso, cumplimiento normativo y operación segura en entornos Enterprise SaaS.

---

# Alcance

Aplica a:

```text
Backend

Frontend

APIs

Mobile

Database

Storage

AI

AWS

Redis

Queues

Integraciones Externas
```

---

# Principios de Seguridad

IAtechs Pro adopta:

```text
Zero Trust

Least Privilege

Defense In Depth

Secure By Default

Tenant Isolation
```

---

# Regla Fundamental

```text
Ningún usuario podrá acceder a recursos que no le pertenezcan.
```

---

# Autenticación

Proveedor oficial:

```text
Laravel Sanctum
```

---

# Métodos Permitidos

```text
Email + Password

API Tokens

Future SSO
```

---

# Contraseñas

Estándar mínimo:

```text
8 caracteres
```

---

# Recomendado

```text
12 caracteres

Mayúsculas

Minúsculas

Números

Símbolos
```

---

# Hashing

Todas las contraseñas deben almacenarse usando:

```php
Hash::make()
```

---

# Nunca

```text
Texto plano

MD5

SHA1
```

---

# Autorización

Proveedor oficial:

```text
Spatie Permission
```

---

# Roles Oficiales

```text
super_admin

owner

manager

technician

customer
```

---

# Permisos

Formato:

```text
module.action
```

---

# Ejemplos

```text
tickets.view

tickets.create

tickets.update

tickets.delete
```

---

# Policies

Toda acción sensible debe utilizar:

```php
Policy
```

---

# Ejemplos

```text
TicketPolicy

InvoicePolicy

CustomerPolicy
```

---

# Multi Tenant Security

Todo dato empresarial debe pertenecer a:

```text
company_id
```

---

# Validación Obligatoria

```text
Tenant Middleware

Tenant Scope

Policies

Ownership Validation
```

---

# Prohibido

```text
Acceso cruzado entre empresas
```

---

# Tenant Isolation

Validar siempre:

```text
Usuario

Empresa

Permisos

Propiedad
```

---

# Ejemplo

```text
Empresa A

NO puede acceder

Empresa B
```

---

# Protección de APIs

Toda API privada requiere:

```text
Authentication

Authorization

Rate Limiting
```

---

# Header

```http
Authorization: Bearer TOKEN
```

---

# Rate Limiting

Estándar:

```text
60 requests/min
```

---

# Endpoints Críticos

```text
Login

Payments

AI

Webhooks
```

---

# Rate Limit Especial

```text
20 requests/min
```

---

# Variables Sensibles

Nunca almacenar credenciales en código.

---

# Correcto

```env
OPENAI_API_KEY=

AWS_SECRET_ACCESS_KEY=

DB_PASSWORD=
```

---

# Incorrecto

```php
$secret = "123456";
```

---

# Encriptación

Utilizar:

```php
Crypt::encryptString()

Crypt::decryptString()
```

---

# Datos a Encriptar

```text
Tokens

Secrets

API Keys

Credentials
```

---

# Base de Datos

Toda tabla empresarial debe incluir:

```text
company_id
```

---

# Prohibido

```sql
SELECT * FROM tickets
```

sin tenant isolation.

---

# Correcto

```sql
WHERE company_id = ?
```

---

# SQL Injection

Nunca:

```php
DB::select(
    "SELECT * FROM users WHERE id = $id"
);
```

---

# Correcto

```php
User::find($id);
```

---

# XSS Protection

Escapar siempre contenido dinámico.

---

# Correcto

```blade
{{ $name }}
```

---

# Incorrecto

```blade
{!! $name !!}
```

---

# CSRF

Toda ruta web protegida debe usar:

```php
@csrf
```

---

# Session Security

Configuración obligatoria:

```text
HttpOnly

Secure

SameSite=Lax
```

---

# File Upload Security

Validar:

```text
Tipo

Extensión

Tamaño

Contenido
```

---

# Permitidos

```text
jpg

jpeg

png

pdf

xlsx

csv
```

---

# Prohibidos

```text
exe

bat

cmd

php

js
```

---

# Storage Security

Estructura oficial:

```text
companies/

├── 1/
├── 2/
├── 3/
└── n/
```

---

# Ejemplo

```text
companies/15/invoices

companies/15/contracts

companies/15/tickets
```

---

# AWS Security

Servicios:

```text
S3

RDS

Redis

CloudWatch
```

---

# Reglas

```text
Private Buckets

IAM Least Privilege

Encrypted Storage
```

---

# Redis Security

Nunca almacenar:

```text
Passwords

Tokens

Secrets
```

sin cifrado.

---

# Queue Security

Todos los Jobs deben contener:

```text
company_id
```

---

# AI Security

Toda integración IA debe registrar:

```text
Provider

Model

Tokens

Cost

Company
```

---

# Restricciones IA

La IA nunca debe:

```text
Compartir datos entre empresas

Exponer secretos

Exponer prompts internos
```

---

# Logging

Registrar:

```text
Login

Logout

Password Reset

Permission Changes

Tenant Access

AI Usage

Payments
```

---

# Nunca Registrar

```text
Passwords

Tokens

Secrets

API Keys
```

---

# Auditoría

Eventos obligatorios:

```text
User Login

User Logout

Role Assigned

Permission Granted

Payment Created

Tenant Access
```

---

# Monitoreo

Registrar:

```text
Failed Logins

Suspicious Access

Rate Limit Violations

Cross Tenant Attempts
```

---

# Backup

Política:

```text
Daily

Encrypted

Retención 30 días
```

---

# Disaster Recovery

Objetivo:

```text
RPO < 24 horas

RTO < 4 horas
```

---

# Seguridad de Código

Herramientas obligatorias:

```text
Laravel Pint

PHPStan

Composer Audit
```

---

# Dependencias

Actualizar periódicamente:

```bash
composer audit
```

---

# Testing de Seguridad

Validar:

```text
Authentication

Authorization

Tenant Isolation

Permissions

Rate Limiting
```

---

# Casos Obligatorios

```text
Unauthorized Access Test

Cross Tenant Access Test

Permission Escalation Test

Rate Limit Test
```

---

# Reglas Prohibidas

Nunca:

```php
Auth::user()->is_admin
```

para autorización.

---

Siempre:

```php
$user->can(...)
```

---

Nunca:

```php
$guarded = [];
```

---

Nunca:

```php
dd()

dump()

var_dump()
```

en producción.

---

# Flujo de Seguridad

```text
Request
 ↓
Middleware
 ↓
Authentication
 ↓
Tenant Validation
 ↓
Authorization
 ↓
Validation
 ↓
Service
 ↓
Repository
 ↓
Database
```

---

# Resultado Esperado

IAtechs Pro deberá operar bajo estándares Enterprise SaaS de seguridad, garantizando aislamiento Multi-Tenant, protección de datos, control de acceso robusto, trazabilidad completa y resistencia frente a amenazas comunes de aplicaciones web modernas.
