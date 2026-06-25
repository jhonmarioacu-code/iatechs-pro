# IAtechs Pro

# Development Standards

## 06-API-Standards

---

# Objetivo

Definir el estándar oficial para el diseño, desarrollo, documentación, seguridad y consumo de APIs dentro de IAtechs Pro.

---

# Alcance

Aplica a:

```text
REST APIs

Mobile APIs

Frontend APIs

Third Party Integrations

AI APIs

Internal APIs
```

---

# Arquitectura

IAtechs Pro utiliza:

```text
RESTful API

JSON

Laravel 12

Sanctum Authentication

Multi-Tenant SaaS
```

---

# Versionado

Todas las APIs deben estar versionadas.

Formato:

```text
/api/v1/
```

---

# Correcto

```http
GET /api/v1/companies

GET /api/v1/tickets

POST /api/v1/customers
```

---

# Incorrecto

```http
/api/companies

/api/tickets
```

---

# Formato de URL

Utilizar:

```text
kebab-case
plural
```

---

# Correcto

```http
/customer-devices

/service-contracts

/purchase-orders
```

---

# Incorrecto

```http
/customerDevices

/serviceContracts
```

---

# Métodos HTTP

## GET

Consultar recursos.

```http
GET /tickets
```

---

## POST

Crear recursos.

```http
POST /tickets
```

---

## PUT

Actualizar completamente.

```http
PUT /tickets/15
```

---

## PATCH

Actualizar parcialmente.

```http
PATCH /tickets/15
```

---

## DELETE

Eliminar recurso.

```http
DELETE /tickets/15
```

---

# Respuesta Estándar

Toda respuesta debe seguir el mismo formato.

---

# Success

```json
{
  "success": true,
  "message": "Operation completed successfully.",
  "data": {}
}
```

---

# Collection

```json
{
  "success": true,
  "message": "Records retrieved successfully.",
  "data": []
}
```

---

# Error

```json
{
  "success": false,
  "message": "Validation failed.",
  "errors": {}
}
```

---

# Status Codes

## 200

```text
OK
```

---

## 201

```text
Created
```

---

## 204

```text
No Content
```

---

## 400

```text
Bad Request
```

---

## 401

```text
Unauthorized
```

---

## 403

```text
Forbidden
```

---

## 404

```text
Not Found
```

---

## 422

```text
Validation Error
```

---

## 500

```text
Internal Server Error
```

---

# Paginación

Formato oficial:

```json
{
  "success": true,
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 15,
    "total": 120,
    "last_page": 8
  }
}
```

---

# Ordenamiento

Parámetro:

```http
?sort=name
```

---

# Descendente

```http
?sort=-created_at
```

---

# Filtros

Formato:

```http
?status=open

?customer_id=15

?company_id=1
```

---

# Búsqueda

Formato:

```http
?q=laptop
```

---

# Recursos

Utilizar siempre:

```php
JsonResource
```

---

# Correcto

```php
TicketResource
CustomerResource
InvoiceResource
```

---

# Incorrecto

```php
return response()->json($model);
```

---

# Validación

Toda entrada debe usar:

```php
FormRequest
```

---

# Correcto

```php
StoreTicketRequest

UpdateCustomerRequest
```

---

# Autenticación

Proveedor oficial:

```text
Laravel Sanctum
```

---

# Header

```http
Authorization: Bearer TOKEN
```

---

# Multi Tenant

Toda petición autenticada deberá respetar:

```text
company_id
```

---

# Regla

```text
No se permite acceso cruzado entre empresas.
```

---

# Ejemplo

```http
GET /api/v1/tickets
```

debe retornar únicamente tickets de la empresa autenticada.

---

# Permisos

Todos los endpoints protegidos deben validar:

```text
Policies

Permissions

Roles
```

---

# Ejemplo

```php
tickets.view

tickets.create

tickets.update

tickets.delete
```

---

# API Resources

Ubicación:

```text
app/Domains/*/Resources
```

---

# Convención

```php
TicketResource

InvoiceResource

CompanyResource
```

---

# API Controllers

Ubicación:

```text
app/Domains/*/Controllers/Api
```

---

# Convención

```php
TicketController

InvoiceController

CustomerController
```

---

# Documentación

Toda API debe estar documentada.

Herramienta oficial:

```text
OpenAPI

Swagger
```

---

# Ejemplo

```http
GET /api/v1/tickets
```

Debe incluir:

```text
Descripción

Parámetros

Permisos

Respuesta

Errores
```

---

# Rate Limiting

Protección obligatoria.

---

# Estándar

```text
60 requests por minuto
```

---

# APIs Críticas

```text
Login

Payments

AI

Webhooks
```

---

# Rate Limit Especial

```text
20 requests por minuto
```

---

# Logs

Registrar:

```text
Authentication

Authorization

Errors

AI Usage

Payments
```

---

# Webhooks

Formato:

```text
/api/v1/webhooks/*
```

---

# Ejemplo

```http
/api/v1/webhooks/stripe

/api/v1/webhooks/openai
```

---

# API de IA

Toda integración IA debe registrar:

```text
Provider

Tokens

Cost

Latency

Company
```

---

# Estructura AI Response

```json
{
  "success": true,
  "provider": "openai",
  "model": "gpt",
  "tokens": 150,
  "data": {}
}
```

---

# API Errors

Nunca exponer:

```text
Stack Trace

SQL Queries

Passwords

Secrets

Tokens
```

---

# Incorrecto

```json
{
  "error": "SQLSTATE..."
}
```

---

# Correcto

```json
{
  "success": false,
  "message": "Internal server error."
}
```

---

# Testing

Toda API debe tener:

```text
Feature Tests
```

---

# Ejemplos

```text
CreateTicketApiTest

CreateCustomerApiTest

LoginApiTest
```

---

# Cobertura Mínima

```text
80%
```

---

# Reglas Prohibidas

Nunca:

```php
return Model::all();
```

directamente.

---

Nunca:

```php
return response()->json(
    Ticket::all()
);
```

---

Nunca:

```php
Auth::user()
```

dentro de Repositories.

---

Nunca:

```php
company_id
```

proporcionado manualmente por el cliente.

---

# Flujo Oficial

```text
Request
 ↓
Middleware
 ↓
Authentication
 ↓
Authorization
 ↓
Validation
 ↓
Service
 ↓
Repository
 ↓
Resource
 ↓
Response
```

---

# Resultado Esperado

Todas las APIs de IAtechs Pro deberán ser consistentes, seguras, versionadas, documentadas, escalables y preparadas para integraciones empresariales, aplicaciones móviles y entornos SaaS Multi-Tenant Enterprise.
