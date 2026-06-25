# IAtechs Pro

# Architecture

## 11-Frontend-Architecture.md

---

# Objetivo

Definir la arquitectura Frontend oficial de IAtechs Pro para garantizar una experiencia moderna, escalable, consistente y compatible con todos los módulos de la plataforma SaaS.

---

# Stack Frontend

## Framework

```text
Laravel Blade
```

---

## Estilos

```text
Tailwind CSS
```

---

## Componentes

```text
AlpineJS
```

---

## Build

```text
Vite
```

---

# Arquitectura General

```text
Frontend

├── Admin Portal
├── Company Portal
├── Technician Portal
├── Customer Portal
└── Public Website
```

---

# Layouts

## Admin Layout

Ubicación:

```text
resources/views/layouts/admin.blade.php
```

Responsable de:

* Sidebar
* Topbar
* Notificaciones
* Menú principal
* Dashboard

---

## Company Layout

Ubicación:

```text
resources/views/layouts/company.blade.php
```

Responsable de:

* Operación empresarial
* CRM
* Inventario
* Facturación

---

## Technician Layout

Ubicación:

```text
resources/views/layouts/technician.blade.php
```

Responsable de:

* Tickets
* Reparaciones
* Diagnósticos

---

## Customer Layout

Ubicación:

```text
resources/views/layouts/customer.blade.php
```

Responsable de:

* Seguimiento de tickets
* Facturas
* Portal cliente

---

# Sidebar

## Estructura

```text
Dashboard

CRM
 ├─ Leads
 ├─ Opportunities

Service Desk
 ├─ Customers
 ├─ Devices
 ├─ Tickets

Inventory
 ├─ Products
 ├─ Suppliers

Accounting
 ├─ Accounts
 ├─ Journal Entries

Knowledge Base

AI Assistant

Reports

Settings
```

---

# Topbar

Elementos:

```text
Buscador Global

Notificaciones

Mensajes

IA Assistant

Perfil Usuario

Selector Empresa
```

---

# Sistema de Navegación

## Menús Dinámicos

Controlados por:

```text
Roles
Permisos
Tenant
```

---

# Sistema de Temas

## Light Mode

```text
Modo Claro
```

---

## Dark Mode

```text
Modo Oscuro
```

---

# Sistema de Componentes

## Cards

Usadas en:

```text
Dashboards
KPIs
Resumenes
```

---

## Tables

Usadas en:

```text
CRM
Inventario
Facturación
Tickets
```

---

## Forms

Usadas en:

```text
Crear
Editar
Actualizar
```

---

## Modals

Usadas en:

```text
Confirmaciones
Aprobaciones
Acciones rápidas
```

---

# Dashboard Widgets

Tipos:

```text
KPI Widget

Chart Widget

Table Widget

AI Widget

Notification Widget
```

---

# Frontend Multi-Tenant

Cada vista deberá respetar:

```text
company_id
```

---

# Frontend Permissions

Visibilidad basada en:

```text
Spatie Permissions
Policies
Roles
```

---

# Responsive Design

Compatibilidad:

```text
Desktop

Laptop

Tablet

Mobile
```

---

# Portal Cliente

Módulos:

```text
Mis Tickets

Mis Equipos

Mis Facturas

Base de Conocimiento

Chat IA
```

---

# Portal Técnico

Módulos:

```text
Tickets

Diagnósticos

Reparaciones

Inventario Asignado
```

---

# Integración IA

Acceso rápido desde:

```text
Topbar

Dashboard

Knowledge Base
```

---

# Sistema de Notificaciones

Canales:

```text
In-App

Email

Push

WhatsApp
```

---

# Accesibilidad

Compatibilidad:

```text
Teclado

Lectores de Pantalla

Contraste Alto
```

---

# Seguridad Frontend

Protección:

```text
CSRF

Policies

Permissions

Tenant Validation
```

---

# Reglas de Negocio

## Regla 1

Todo menú debe respetar permisos.

---

## Regla 2

Toda vista debe respetar tenant.

---

## Regla 3

Todo dashboard debe ser responsive.

---

## Regla 4

La IA debe estar disponible desde cualquier módulo autorizado.

---

# Resultado Esperado

IAtechs Pro contará con una interfaz moderna, modular, responsive, multi-tenant y preparada para escalar a nivel empresarial, manteniendo consistencia visual y experiencia de usuario en todos los módulos del sistema.
