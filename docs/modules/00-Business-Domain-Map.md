# Business Domain Map

Fecha: 2026-06-26  
Estado: Oficial

## 1. Objetivo

Definir el mapa canonico de dominios y modulos de IAtechsPro para que negocio y tecnologia tengan la misma fuente de verdad.

## 2. Principio central

Cada empresa registrada es un tenant aislado y opera sobre su propio `company_id`.

## 3. Dominios principales

### Core SaaS

- Companies
- Branches
- Users
- Roles
- Permissions
- Plans
- Subscriptions
- Billing

### CRM

- Customers
- Contacts
- Leads

### Soporte tecnico

- Tickets
- Devices
- Diagnostics
- Repairs
- WorkOrders
- Technicians

### Inventario

- Products
- Categories
- Warehouses
- Inventory
- Stock
- Suppliers
- Purchases

### Comercial y facturacion

- Quotes
- Sales
- Invoices
- Payments

### Administracion

- Dashboard
- Reports
- Notifications
- Audit
- Settings

### IA

- Chat IA
- Diagnostico automatico
- Generacion de soluciones
- Respuestas inteligentes
- Base de conocimiento
- Automatizacion y agentes IA

## 4. Contrato de modulo obligatorio

Cada modulo nuevo o actualizado debe documentar minimo:

1. Objetivo del modulo
2. Casos de uso
3. Reglas de negocio
4. Flujo operativo
5. APIs y contratos
6. Estructura de datos (tablas, indices, constraints)
7. Relaciones entre entidades
8. Eventos y jobs
9. Seguridad (roles, permisos, policies)
10. Pruebas obligatorias
11. Diagramas

## 5. Reglas de aislamiento

- Toda entidad operativa incluye `company_id`.
- No hay consultas de negocio sin filtro tenant.
- No se comparten datos entre empresas.
- No se exponen datos de otro tenant en UI, API, reportes ni jobs.

## 6. Ubicacion documental

- Modulos base: `docs/modules/*`
- Modulos enterprise: `docs/modules-enterprise/*`
- Arquitectura transversal: `docs/architecture/*`
- Estandares: `docs/standards/*`

