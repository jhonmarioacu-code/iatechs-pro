# IAtechs Pro

# Development Standards

## 08-Testing-Standards

---

# Objetivo

Definir el estándar oficial de pruebas de IAtechs Pro para garantizar calidad, estabilidad, seguridad y escalabilidad empresarial.

---

# Alcance

Aplica a:

```text
Backend

APIs

Services

Repositories

Jobs

Events

Policies

Multi-Tenant

AI

Integraciones
```

---

# Estrategia de Testing

IAtechs Pro utiliza:

```text
Pest PHP

PHPUnit

Laravel Testing

Feature Tests

Unit Tests

Integration Tests
```

---

# Objetivos

Garantizar:

```text
Calidad

Seguridad

Escalabilidad

Aislamiento Multi-Tenant

Cobertura de negocio
```

---

# Cobertura Mínima

## Global

```text
80%
```

---

## Módulos Críticos

```text
90%
```

---

# Módulos Críticos

```text
Authentication

Companies

Subscriptions

Billing

Accounting

Payments

AI

Permissions

Multi-Tenant
```

---

# Tipos de Pruebas

## Unit Tests

Validan:

```text
Services

Repositories

DTOs

Enums

Helpers

Policies
```

---

# Feature Tests

Validan:

```text
Endpoints

Requests

Responses

Workflows

Permisos
```

---

# Integration Tests

Validan:

```text
AWS

Redis

PostgreSQL

AI Providers

External APIs
```

---

# End To End

Validan:

```text
Procesos completos
```

---

# Estructura Oficial

```text
tests/

├── Unit
├── Feature
├── Integration
└── Helpers
```

---

# Unit Tests

Ubicación:

```text
tests/Unit
```

---

# Ejemplos

```text
CompanyServiceTest

InvoiceServiceTest

TicketRepositoryTest

PermissionPolicyTest
```

---

# Feature Tests

Ubicación:

```text
tests/Feature
```

---

# Ejemplos

```text
CreateCompanyTest

CreateCustomerTest

CloseTicketTest

GenerateInvoiceTest
```

---

# Integration Tests

Ubicación:

```text
tests/Integration
```

---

# Ejemplos

```text
OpenAIProviderTest

AWSStorageTest

RedisCacheTest

StripePaymentTest
```

---

# Convención de Nombres

Formato:

```text
ActionEntityTest
```

---

# Correcto

```php
CreateTicketTest

CreateInvoiceTest

RegisterPaymentTest
```

---

# Incorrecto

```php
TicketTest

TestInvoice

InvoiceTesting
```

---

# Testing de Services

Todo Service debe tener:

```text
Unit Test
```

---

# Ejemplo

```text
TicketServiceTest

InvoiceServiceTest

CustomerServiceTest
```

---

# Validaciones

Toda Request debe tener pruebas.

---

# Ejemplo

```text
StoreCustomerRequestTest

StoreTicketRequestTest
```

---

# Policies

Toda Policy debe tener pruebas.

---

# Ejemplo

```text
TicketPolicyTest

InvoicePolicyTest

CustomerPolicyTest
```

---

# Repositories

Todo Repository debe tener pruebas.

---

# Ejemplo

```text
TicketRepositoryTest

InvoiceRepositoryTest
```

---

# API Testing

Todas las APIs deben validar:

```text
Success

Validation

Authorization

Authentication

Tenant Isolation
```

---

# Ejemplo

```php
$response
    ->assertStatus(200);
```

---

# Validación de Errores

```php
$response
    ->assertStatus(422);
```

---

# Validación de Permisos

```php
$response
    ->assertForbidden();
```

---

# Validación de Login

```php
$response
    ->assertUnauthorized();
```

---

# Multi Tenant Testing

Obligatorio.

---

# Validar

```text
Tenant Isolation

Cross Access Protection

Tenant Scope
```

---

# Ejemplo

```text
Empresa A

NO puede ver

Empresa B
```

---

# Tests Obligatorios

```text
TenantResolverTest

TenantScopeTest

CrossTenantAccessTest
```

---

# AI Testing

Validar:

```text
Provider

Tokens

Cost

Response

Fallback
```

---

# Ejemplo

```text
OpenAIProviderTest

GeminiProviderTest

OllamaProviderTest
```

---

# Queue Testing

Validar:

```text
Jobs

Retries

Failures
```

---

# Ejemplo

```php
Bus::fake();
```

---

# Eventos

Validar:

```text
Dispatch

Listeners

Payload
```

---

# Ejemplo

```php
Event::fake();
```

---

# Notificaciones

Validar:

```text
Email

Database

Push
```

---

# Ejemplo

```php
Notification::fake();
```

---

# Base de Datos

Utilizar:

```php
RefreshDatabase
```

---

# Correcto

```php
use RefreshDatabase;
```

---

# Factories

Todas las entidades principales deben tener Factory.

---

# Ejemplos

```text
CompanyFactory

UserFactory

TicketFactory

InvoiceFactory
```

---

# Seeders de Testing

Ubicación:

```text
database/seeders/testing
```

---

# Datos de Prueba

Nunca utilizar:

```text
Producción

Datos reales

Credenciales reales
```

---

# Performance Testing

Validar:

```text
Queries

Response Time

Memory Usage
```

---

# Objetivo

```text
< 300 ms
```

para endpoints normales.

---

# Seguridad

Validar:

```text
Authentication

Authorization

Permissions

Tenant Isolation

Rate Limit
```

---

# Casos Críticos

```text
Login

Reset Password

Payments

AI

Subscriptions
```

---

# Automatización

Ejecutar en CI/CD:

```bash
php artisan test
```

---

# Pipeline

Antes de deploy:

```text
Tests

PHPStan

Laravel Pint
```

---

# Reglas Prohibidas

Nunca:

```php
dd()

dump()

die()

exit()
```

en pruebas.

---

Nunca depender de:

```text
Servicios externos reales
```

para Unit Tests.

---

# Mocking

Utilizar:

```php
Mockery
```

---

# Correcto

```php
$repository = Mockery::mock(
    TicketRepository::class
);
```

---

# Flujo Oficial

```text
Code
 ↓
Unit Test
 ↓
Feature Test
 ↓
Integration Test
 ↓
CI/CD
 ↓
Deploy
```

---

# Resultado Esperado

Todo desarrollo de IAtechs Pro deberá estar respaldado por pruebas automatizadas que garanticen estabilidad, calidad, seguridad, cumplimiento Multi-Tenant y preparación para entornos Enterprise SaaS de gran escala.
