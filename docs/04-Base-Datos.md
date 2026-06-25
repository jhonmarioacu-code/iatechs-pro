# Base de Datos

# IAtechs Pro

## Plataforma Empresarial Inteligente para Gestión de Servicios Técnicos

---

# Objetivo

Definir la estructura de base de datos empresarial de IAtechs Pro bajo una arquitectura SaaS Multiempresa, escalable y modular.

La base de datos utilizará:

```text
PostgreSQL
```

Como motor principal.

---

# Convenciones

## Claves Primarias

```sql
id BIGINT
```

## Claves Foráneas

```sql
*_id
```

## Auditoría

Todas las tablas principales incluirán:

```sql
created_at
updated_at
deleted_at
```

---

# Dominio 1: Core SaaS

## companies

```text
id
name
slug
email
phone
tax_id
logo
status
created_at
updated_at
deleted_at
```

---

## company_settings

```text
id
company_id
timezone
currency
language
date_format
created_at
updated_at
```

---

## branches

```text
id
company_id
name
code
address
phone
email
manager_id
created_at
updated_at
deleted_at
```

---

## plans

```text
id
name
description
price
max_users
max_branches
status
created_at
updated_at
```

---

## subscriptions

```text
id
company_id
plan_id
starts_at
expires_at
status
created_at
updated_at
```

---

# Dominio 2: Seguridad

## users

```text
id
company_id
branch_id
name
email
phone
password
status
last_login_at
created_at
updated_at
deleted_at
```

---

## roles

Gestionado por Spatie Permission.

---

## permissions

Gestionado por Spatie Permission.

---

## user_profiles

```text
id
user_id
avatar
address
birth_date
document_number
created_at
updated_at
```

---

## user_sessions

```text
id
user_id
ip_address
device
login_at
logout_at
created_at
updated_at
```

---

# Dominio 3: CRM

## leads

```text
id
company_id
name
email
phone
source
status
notes
created_at
updated_at
```

---

## customers

```text
id
company_id
customer_type
name
document_number
email
phone
address
city
status
created_at
updated_at
deleted_at
```

---

## customer_contacts

```text
id
customer_id
name
position
phone
email
created_at
updated_at
```

---

## opportunities

```text
id
customer_id
title
value
status
expected_close_date
created_at
updated_at
```

---

# Dominio 4: Equipos

## device_categories

```text
id
name
description
created_at
updated_at
```

---

## devices

```text
id
customer_id
device_category_id
brand
model
serial_number
imei
purchase_date
status
created_at
updated_at
```

---

## device_photos

```text
id
device_id
path
created_at
updated_at
```

---

# Dominio 5: Operaciones Técnicas

## tickets

```text
id
company_id
branch_id
customer_id
device_id
ticket_number
status
priority
received_at
created_at
updated_at
```

---

## diagnostics

```text
id
ticket_id
technician_id
problem_description
diagnosis
estimated_cost
estimated_time
status
created_at
updated_at
```

---

## quotes

```text
id
ticket_id
quote_number
subtotal
tax
total
status
approved_at
created_at
updated_at
```

---

## quote_items

```text
id
quote_id
product_id
description
quantity
unit_price
total
created_at
updated_at
```

---

## work_orders

```text
id
ticket_id
assigned_to
start_date
end_date
status
created_at
updated_at
```

---

## repairs

```text
id
work_order_id
description
solution
status
completed_at
created_at
updated_at
```

---

## quality_controls

```text
id
repair_id
reviewed_by
observations
approved
created_at
updated_at
```

---

## warranties

```text
id
repair_id
start_date
end_date
status
created_at
updated_at
```

---

# Dominio 6: Inventario

## product_categories

```text
id
name
description
created_at
updated_at
```

---

## products

```text
id
company_id
category_id
sku
name
description
cost_price
sale_price
stock_min
status
created_at
updated_at
```

---

## warehouses

```text
id
company_id
name
location
created_at
updated_at
```

---

## inventory_stocks

```text
id
warehouse_id
product_id
quantity
created_at
updated_at
```

---

## inventory_movements

```text
id
product_id
warehouse_id
movement_type
quantity
reference
created_at
updated_at
```

---

# Dominio 7: Compras

## suppliers

```text
id
company_id
name
tax_id
phone
email
address
status
created_at
updated_at
```

---

## purchase_orders

```text
id
supplier_id
order_number
status
subtotal
tax
total
created_at
updated_at
```

---

## purchase_order_items

```text
id
purchase_order_id
product_id
quantity
cost
created_at
updated_at
```

---

# Dominio 8: Finanzas

## invoices

```text
id
customer_id
invoice_number
subtotal
tax
total
status
issued_at
created_at
updated_at
```

---

## invoice_items

```text
id
invoice_id
product_id
description
quantity
price
total
created_at
updated_at
```

---

## payments

```text
id
invoice_id
payment_method
amount
paid_at
created_at
updated_at
```

---

## expenses

```text
id
company_id
category
description
amount
expense_date
created_at
updated_at
```

---

# Dominio 9: Field Service GPS

## service_requests

```text
id
customer_id
address
latitude
longitude
status
created_at
updated_at
```

---

## service_assignments

```text
id
service_request_id
technician_id
assigned_at
status
created_at
updated_at
```

---

## gps_tracks

```text
id
technician_id
latitude
longitude
tracked_at
created_at
updated_at
```

---

# Dominio 10: Portal Cliente

## customer_notifications

```text
id
customer_id
title
message
read_at
created_at
updated_at
```

---

## customer_messages

```text
id
customer_id
ticket_id
message
sender
created_at
updated_at
```

---

# Dominio 11: Inteligencia Artificial

## ai_conversations

```text
id
company_id
user_id
conversation_type
created_at
updated_at
```

---

## ai_messages

```text
id
conversation_id
role
content
created_at
updated_at
```

---

## knowledge_articles

```text
id
company_id
title
content
category
created_at
updated_at
```

---

# Dominio 12: Analytics

## dashboards

```text
id
company_id
name
created_at
updated_at
```

---

## reports

```text
id
company_id
name
report_type
generated_at
created_at
updated_at
```

---

# Dominio 13: Auditoría

## audit_logs

```text
id
company_id
user_id
action
table_name
record_id
old_values
new_values
ip_address
created_at
```

---

# Resumen General

```text
Core SaaS
Seguridad
CRM
Equipos
Operaciones Técnicas
Inventario
Compras
Finanzas
Field Service GPS
Portal Cliente
Inteligencia Artificial
Analytics
Auditoría
```

---

# Motor Oficial

```text
PostgreSQL
```

---

# Resultado Esperado

La base de datos de IAtechs Pro debe soportar una plataforma SaaS multiempresa, escalable y modular, preparada para miles de usuarios, múltiples sucursales, operaciones técnicas complejas, inteligencia artificial y crecimiento empresarial a largo plazo.
