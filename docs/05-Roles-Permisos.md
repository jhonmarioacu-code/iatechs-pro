# Roles y Permisos

# IAtechs Pro

## Plataforma Empresarial Inteligente para Gestión de Servicios Técnicos

---

# Objetivo

Definir la estructura de acceso, seguridad y autorización de IAtechs Pro mediante un sistema RBAC (Role Based Access Control) utilizando Spatie Permission.

Cada usuario tendrá uno o varios roles y cada rol tendrá permisos específicos sobre los módulos del sistema.

---

# Jerarquía General

```text
Super Admin
│
├── Company Owner
│
├── Administrator
│
├── Technical Manager
│
├── Receptionist
│
├── Inventory Manager
│
├── Accountant
│
├── Local Technician
│
├── Field Technician
│
└── Customer
```

---

# 1. Super Admin

## Descripción

Administrador global de toda la plataforma SaaS.

## Acceso

* Todas las empresas.
* Todas las sucursales.
* Todos los módulos.
* Configuración global.
* Facturación SaaS.
* Planes.
* Suscripciones.

## Permisos

```text
system.*
company.*
branch.*
subscription.*
plan.*
user.*
role.*
permission.*
audit.*
report.*
```

---

# 2. Company Owner

## Descripción

Propietario de una empresa registrada.

## Acceso

* Su empresa.
* Todas sus sucursales.
* Todos los módulos internos.

## Permisos

```text
company.view
company.update

branch.*

user.*

crm.*

operations.*

inventory.*

purchases.*

finance.*

analytics.*

client_portal.*

field_service.*
```

---

# 3. Administrator

## Descripción

Administrador operativo de la empresa.

## Permisos

```text
user.view
user.create
user.update

crm.*

operations.*

inventory.*

purchases.*

finance.view

reports.view
```

---

# 4. Technical Manager

## Descripción

Supervisor del área técnica.

## Permisos

```text
customers.view

devices.*

tickets.*

diagnostics.*

quotes.*

work_orders.*

repairs.*

warranties.*

technicians.assign
```

---

# 5. Receptionist

## Descripción

Recepción y atención al cliente.

## Permisos

```text
customers.*

devices.*

tickets.create
tickets.view
tickets.update

quotes.view

deliveries.create
deliveries.view
```

---

# 6. Inventory Manager

## Descripción

Responsable del inventario.

## Permisos

```text
products.*

categories.*

warehouses.*

stock.*

kardex.*

suppliers.*

purchases.*
```

---

# 7. Accountant

## Descripción

Gestión financiera.

## Permisos

```text
invoices.*

payments.*

expenses.*

financial_reports.*

taxes.*
```

---

# 8. Local Technician

## Descripción

Técnico interno del taller.

## Permisos

```text
tickets.view

diagnostics.*

work_orders.*

repairs.*

technical_history.view

inventory.view
```

---

# 9. Field Technician

## Descripción

Técnico de campo o domicilio.

## Permisos

```text
service_requests.view

assignments.view

gps.view

routes.view

evidence.*

signatures.*

mobile_operations.*
```

---

# 10. Customer

## Descripción

Cliente final.

## Permisos

```text
my_devices.view

my_repairs.view

my_quotes.view

my_invoices.view

my_warranties.view

messages.create

messages.view
```

---

# Permisos por Dominio

## Core SaaS

```text
company.view
company.create
company.update
company.delete

branch.view
branch.create
branch.update
branch.delete

subscription.view
subscription.create
subscription.update
subscription.delete

plan.view
plan.create
plan.update
plan.delete
```

---

## Usuarios

```text
user.view
user.create
user.update
user.delete

role.view
role.create
role.update
role.delete

permission.view
permission.assign
```

---

## CRM

```text
lead.view
lead.create
lead.update
lead.delete

customer.view
customer.create
customer.update
customer.delete

opportunity.view
opportunity.create
opportunity.update
opportunity.delete
```

---

## Operaciones Técnicas

```text
device.view
device.create
device.update
device.delete

ticket.view
ticket.create
ticket.update
ticket.close

diagnostic.view
diagnostic.create
diagnostic.update

quote.view
quote.create
quote.approve

work_order.view
work_order.create
work_order.assign

repair.view
repair.create
repair.complete

warranty.view
warranty.create
```

---

## Inventario

```text
product.view
product.create
product.update
product.delete

warehouse.view
warehouse.create
warehouse.update

stock.view
stock.adjust

supplier.view
supplier.create
supplier.update

purchase.view
purchase.create
purchase.approve
```

---

## Finanzas

```text
invoice.view
invoice.create
invoice.update

payment.view
payment.create

expense.view
expense.create
expense.update

financial_report.view
```

---

## Field Service

```text
service_request.view
service_request.create

assignment.view
assignment.create

route.view

gps.view

evidence.create

signature.create
```

---

## Portal Cliente

```text
my_devices.view

my_repairs.view

my_quotes.view

my_invoices.view

my_warranties.view
```

---

## Inteligencia Artificial

```text
ai.chat

ai.diagnostic

ai.repair

ai.reports

ai.analytics
```

---

## Analytics

```text
dashboard.view

kpi.view

reports.view

forecast.view
```

---

# Seguridad Adicional

## Auditoría

Todas las acciones críticas serán registradas.

Eventos auditables:

* Login.
* Logout.
* Creación.
* Edición.
* Eliminación.
* Aprobaciones.
* Facturación.
* Pagos.

---

## Políticas Laravel

Cada recurso deberá implementar:

```php
Policy
```

Ejemplos:

```text
CustomerPolicy
TicketPolicy
InvoicePolicy
ProductPolicy
UserPolicy
```

---

## Middleware

```text
auth
verified
role
permission
subscription.active
company.active
```

---

# Resultado Esperado

IAtechs Pro contará con un sistema de seguridad robusto basado en roles y permisos, garantizando que cada usuario acceda únicamente a la información y funcionalidades autorizadas dentro de su empresa y rol operativo.
