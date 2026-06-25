# IAtechs Pro

# Architecture

## 05-API-Architecture.md

---

# Objetivo

Definir la arquitectura oficial de APIs REST para IAtechs Pro, garantizando seguridad, escalabilidad, consistencia, versionado y compatibilidad con aplicaciones web, móviles y servicios externos.

---

# Estándar API

## Tipo

```text
REST API
```

---

## Formato

```text
JSON
```

---

## Codificación

```text
UTF-8
```

---

# Base URL

## Producción

```text
https://api.iatechspro.com/api/v1
```

---

## Desarrollo

```text
http://localhost:8000/api/v1
```

---

# Versionado

## Estrategia

```text
/api/v1
/api/v2
/api/v3
```

---

# Regla

Nunca romper compatibilidad de versiones existentes.

---

# Autenticación

## Tecnología

```text
Laravel Sanctum
```

---

# Flujo

```text
Login
   ↓
Token
   ↓
Authorization Bearer Token
   ↓
API
```

---

# Header Obligatorio

```http
Authorization: Bearer TOKEN
Accept: application/json
Content-Type: application/json
```

---

# Multi-Tenant

Toda petición autenticada deberá resolver:

```text
company_id
```

---

# Flujo Tenant

```text
Usuario
   ↓
Token
   ↓
Company
   ↓
Tenant Middleware
   ↓
Acceso API
```

---

# Estructura de Respuesta

## Success

```json
{
  "success": true,
  "message": "Operación realizada correctamente",
  "data": {}
}
```

---

## Error

```json
{
  "success": false,
  "message": "Error de validación",
  "errors": []
}
```

---

# Códigos HTTP

```text
200 OK
201 Created
204 No Content

400 Bad Request
401 Unauthorized
403 Forbidden
404 Not Found
422 Validation Error

429 Too Many Requests

500 Internal Server Error
```

---

# Recursos Principales

```text
/auth
/companies
/users
/customers
/devices
/tickets
/diagnostics
/quotes
/repairs
/inventory
/suppliers
/purchases
/invoices
/payments
/reports
/analytics
/files
/notifications
/ai
/settings
```

---

# Autenticación

## Endpoints

```http
POST   /auth/login
POST   /auth/logout
POST   /auth/refresh
GET    /auth/profile
PUT    /auth/profile
```

---

# CRUD Estándar

## Ejemplo Customers

```http
GET      /customers
GET      /customers/{id}

POST     /customers

PUT      /customers/{id}

DELETE   /customers/{id}
```

---

# Paginación

## Formato

```http
GET /customers?page=1&per_page=20
```

---

## Respuesta

```json
{
  "data": [],
  "meta": {
    "current_page": 1,
    "per_page": 20,
    "total": 100
  }
}
```

---

# Filtros

## Ejemplo

```http
GET /tickets?status=open
```

---

## Múltiples

```http
GET /tickets?status=open&priority=high
```

---

# Ordenamiento

```http
GET /tickets?sort=created_at
```

---

## Descendente

```http
GET /tickets?sort=-created_at
```

---

# Búsquedas

```http
GET /customers?search=jhon
```

---

# Rate Limiting

## Usuarios

```text
120 requests/minuto
```

---

## API Pública

```text
60 requests/minuto
```

---

# Middleware API

```text
auth:sanctum
tenant
throttle
permission
audit
```

---

# Seguridad

## Validaciones

```text
Authentication
Authorization
Tenant Validation
Input Validation
Audit Logs
```

---

# API Resources

Ubicación:

```text
app/Http/Resources/
```

---

# Ejemplos

```text
CustomerResource
TicketResource
InvoiceResource
UserResource
```

---

# Requests

Ubicación:

```text
app/Http/Requests/
```

---

# Ejemplos

```text
StoreCustomerRequest
UpdateCustomerRequest
StoreTicketRequest
StoreInvoiceRequest
```

---

# API Documentation

## Tecnología

```text
OpenAPI 3.0
Swagger
```

---

# URL

```text
/api/documentation
```

---

# Webhooks

## Eventos

```text
InvoicePaid
TicketCreated
RepairCompleted
CustomerCreated
```

---

# Endpoint

```http
POST /webhooks
```

---

# Integraciones

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
WhatsApp Business
Firebase
```

---

## AWS

```text
S3
SES
SNS
```

---

# API de Archivos

## Upload

```http
POST /files
```

---

## Download

```http
GET /files/{id}/download
```

---

# API IA

## Chat

```http
POST /ai/chat
```

---

## Conversaciones

```http
GET /ai/conversations
```

---

## Automatizaciones

```http
POST /ai/automations
```

---

# Auditoría

Registrar:

```text
API Access
Failed Access
Unauthorized Requests
Rate Limit Exceeded
Webhook Events
```

---

# Monitoreo

## CloudWatch

```text
Requests
Latency
Errors
Bandwidth
Rate Limits
```

---

# Testing

## Unit Tests

```text
ApiAuthenticationTest
ApiValidationTest
ApiResourceTest
```

---

## Feature Tests

```text
CustomerApiTest
TicketApiTest
InvoiceApiTest
AuthApiTest
```

---

# Estructura de Rutas

```text
routes/

api.php

api/v1/

AuthRoutes.php
CustomerRoutes.php
TicketRoutes.php
InvoiceRoutes.php
ReportRoutes.php
AIRoutes.php
```

---

# Reglas de Negocio

## Regla 1

Toda API debe estar versionada.

---

## Regla 2

Toda API autenticada debe validar tenant.

---

## Regla 3

Toda API debe registrar auditoría.

---

## Regla 4

Toda respuesta debe seguir formato estándar.

---

## Regla 5

No exponer información sensible.

---

## Regla 6

Toda integración externa debe autenticarse.

---

# Arquitectura Recomendada

```text
Controller
     ↓
Request
     ↓
DTO
     ↓
Service
     ↓
Repository
     ↓
Model
     ↓
Resource
     ↓
Response
```

---

# Resultado Esperado

La arquitectura API de IAtechs Pro permitirá una comunicación segura, escalable y estandarizada entre frontend, aplicaciones móviles, servicios de IA, integraciones externas y módulos internos, garantizando compatibilidad futura y crecimiento empresarial.
