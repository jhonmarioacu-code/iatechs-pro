# IAtechs Pro

# Module Specification

## 37-Procurement

---

# Objetivo

Administrar el proceso completo de abastecimiento corporativo, desde la solicitud de compra hasta la recepción de bienes y evaluación de proveedores, garantizando control, trazabilidad y cumplimiento de políticas empresariales.

---

# Nombre Técnico

```text
Procurement
```

---

# Descripción

El módulo Procurement extiende las funcionalidades de Compras de IAtechs Pro mediante flujos empresariales de aprobación, licitación, contratos y evaluación de proveedores.

Permite:

* Solicitudes de compra.
* Aprobaciones.
* Solicitudes de cotización (RFQ).
* Comparación de ofertas.
* Contratos de proveedores.
* Órdenes de compra.
* Recepción de mercancía.
* Evaluación de proveedores.

---

# Componentes

## Purchase Requests

Solicitudes internas de compra.

---

## RFQ

Request For Quotation.

---

## Supplier Quotations

Cotizaciones de proveedores.

---

## Purchase Approvals

Flujo de aprobación.

---

## Supplier Contracts

Contratos comerciales.

---

## Vendor Evaluation

Evaluación de proveedores.

---

# Integración con Módulos Existentes

```text
Suppliers

Purchases

PurchaseOrders

GoodsReceipts

Inventory

Accounting
```

---

# Tablas

```text
purchase_requests

purchase_request_items

purchase_approvals

rfqs

supplier_quotations

supplier_contracts

supplier_evaluations
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Procurement Manager

Gestión completa.

---

## Purchasing Agent

Operación de compras.

---

## Department Manager

Aprobaciones.

---

# Estados Solicitud

```text
draft

submitted

approved

rejected

converted
```

---

# Estados RFQ

```text
draft

sent

received

evaluated

awarded

closed
```

---

# Estados Contrato

```text
draft

active

expired

terminated
```

---

# Relaciones

## PurchaseRequest → Items

```text
1:N
```

---

## PurchaseRequest → Approvals

```text
1:N
```

---

## RFQ → Quotations

```text
1:N
```

---

## Supplier → Contracts

```text
1:N
```

---

## Supplier → Evaluations

```text
1:N
```

---

# Campos Principales

## PurchaseRequest

```text
id

company_id

requested_by

department_id

request_number

request_date

status

total_amount
```

---

## RFQ

```text
id

company_id

purchase_request_id

rfq_number

closing_date

status
```

---

## SupplierQuotation

```text
id

rfq_id

supplier_id

quotation_number

total_amount

status
```

---

## SupplierContract

```text
id

supplier_id

contract_number

start_date

end_date

status
```

---

## SupplierEvaluation

```text
id

supplier_id

score

comments

evaluation_date
```

---

# Modelos

## PurchaseRequest

```text
app/Domains/Procurement/Models/PurchaseRequest.php
```

---

## RFQ

```text
app/Domains/Procurement/Models/RFQ.php
```

---

## SupplierQuotation

```text
app/Domains/Procurement/Models/SupplierQuotation.php
```

---

## SupplierContract

```text
app/Domains/Procurement/Models/SupplierContract.php
```

---

## SupplierEvaluation

```text
app/Domains/Procurement/Models/SupplierEvaluation.php
```

---

# Repositories

```text
PurchaseRequestRepository.php

RFQRepository.php

SupplierEvaluationRepository.php
```

---

# Service

```text
ProcurementService.php
```

---

# Responsabilidades

* Gestionar solicitudes.
* Gestionar aprobaciones.
* Gestionar RFQ.
* Comparar cotizaciones.
* Seleccionar proveedores.
* Gestionar contratos.
* Evaluar desempeño.

---

# Controller

```text
ProcurementController.php
```

---

# Requests

```text
StorePurchaseRequest.php

ApprovePurchaseRequest.php

StoreRFQRequest.php

StoreSupplierEvaluationRequest.php
```

---

# Resources

```text
PurchaseRequestResource.php

RFQResource.php
```

---

# Policy

```text
ProcurementPolicy.php
```

---

# Permisos

```text
procurement.view

procurement.create

procurement.update

procurement.approve

procurement.rfq

procurement.contracts

procurement.export
```

---

# Endpoints Web

```http
GET     /procurement

GET     /purchase-requests

POST    /purchase-requests

GET     /rfqs

POST    /rfqs
```

---

# Endpoints API

```http
GET     /api/v1/procurement/purchase-requests

POST    /api/v1/procurement/purchase-requests

GET     /api/v1/procurement/rfqs

POST    /api/v1/procurement/rfqs

GET     /api/v1/procurement/contracts
```

---

# Workflow

## Solicitud de Compra

```text
Draft
 ↓
Submitted
 ↓
Approved
 ↓
RFQ
 ↓
Purchase Order
 ↓
Goods Receipt
```

---

## Contratación

```text
RFQ
 ↓
Supplier Selection
 ↓
Contract
 ↓
Purchase
```

---

# Integraciones

## Suppliers

```text
Supplier
 ↓
Quotation
 ↓
Contract
```

---

## Purchases

```text
Approved Request
 ↓
Purchase Order
```

---

## Inventory

```text
Goods Receipt
 ↓
Stock Update
```

---

## Accounting

```text
Purchase
 ↓
Journal Entry
```

---

# Dashboard

Widgets:

```text
Purchase Requests

Pending Approvals

Open RFQs

Supplier Performance

Contract Expirations

Procurement Spend
```

---

# Analytics

KPIs:

```text
Total Procurement Spend

Approval Time

Supplier Score

Savings Achieved

Contract Compliance

Procurement Cycle Time
```

---

# Auditoría

Eventos:

```text
Purchase Request Created

Request Approved

RFQ Created

Supplier Selected

Contract Signed

Supplier Evaluated
```

---

# Testing

## Unit Tests

```text
PurchaseRequestRepositoryTest

RFQRepositoryTest

ProcurementServiceTest
```

---

## Feature Tests

```text
PurchaseApprovalTest

RFQWorkflowTest

SupplierEvaluationTest
```

---

# Reglas de Negocio

## Regla 1

Toda solicitud debe pertenecer a una empresa.

---

## Regla 2

Toda compra superior al límite definido requiere aprobación.

---

## Regla 3

Toda RFQ debe tener al menos un proveedor.

---

## Regla 4

Toda evaluación debe quedar auditada.

---

## Regla 5

Toda consulta debe respetar company_id.

---

# KPI del Módulo

* Gasto total.
* Solicitudes aprobadas.
* Tiempo promedio de aprobación.
* Ahorro por negociación.
* Evaluación promedio de proveedores.
* Contratos activos.

---

# Resultado Esperado

El módulo Procurement permitirá administrar el abastecimiento empresarial de IAtechs Pro con procesos estructurados de aprobación, licitación, contratación y evaluación, garantizando control financiero, cumplimiento corporativo y optimización de compras.
