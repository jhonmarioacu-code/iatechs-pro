# IAtechs Pro

# Module Specification

## 36-Assets

---

# Objetivo

Administrar los activos empresariales de cada organización registrada en IAtechs Pro, permitiendo controlar adquisición, asignación, mantenimiento, depreciación, ubicación y ciclo de vida de los bienes corporativos.

---

# Nombre Técnico

```text
Assets
```

---

# Descripción

El módulo Assets permite gestionar todos los activos físicos y digitales de una empresa.

Permite:

* Registrar activos.
* Clasificar activos.
* Asignar activos.
* Gestionar mantenimientos.
* Controlar depreciación.
* Gestionar bajas.
* Controlar ubicación.
* Generar reportes patrimoniales.

---

# Componentes

## Assets

Activos.

---

## Asset Categories

Categorías.

---

## Asset Assignments

Asignaciones.

---

## Asset Maintenance

Mantenimientos.

---

## Asset Depreciation

Depreciaciones.

---

# Tablas

```text
assets

asset_categories

asset_assignments

asset_maintenance

asset_depreciations
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Asset Manager

Gestión completa.

---

## Manager

Consulta y aprobación.

---

## Employee

Consulta de activos asignados.

---

# Tipos de Activos

```text
equipment

computer

server

vehicle

furniture

license

software

tool

other
```

---

# Estados

```text
active

assigned

maintenance

retired

disposed
```

---

# Relaciones

## AssetCategory → Assets

```text
1:N
```

---

## Asset → Assignments

```text
1:N
```

---

## Asset → Maintenance

```text
1:N
```

---

## Employee → Assignments

```text
1:N
```

---

# Campos Principales

## Asset

```text
id

company_id

category_id

asset_code

serial_number

name

description

purchase_date

purchase_cost

current_value

status
```

---

## Asset Category

```text
id

company_id

name

description
```

---

## Asset Assignment

```text
id

asset_id

employee_id

assigned_at

returned_at

notes
```

---

## Asset Maintenance

```text
id

asset_id

maintenance_date

description

cost

status
```

---

## Asset Depreciation

```text
id

asset_id

period

amount

book_value
```

---

# Modelos

## Asset

```text
app/Domains/Assets/Models/Asset.php
```

---

## AssetCategory

```text
app/Domains/Assets/Models/AssetCategory.php
```

---

## AssetAssignment

```text
app/Domains/Assets/Models/AssetAssignment.php
```

---

## AssetMaintenance

```text
app/Domains/Assets/Models/AssetMaintenance.php
```

---

## AssetDepreciation

```text
app/Domains/Assets/Models/AssetDepreciation.php
```

---

# Repositories

```text
AssetRepository.php

AssetMaintenanceRepository.php
```

---

# Service

```text
AssetsService.php
```

---

# Responsabilidades

* Crear activos.
* Asignar activos.
* Registrar mantenimientos.
* Calcular depreciaciones.
* Gestionar bajas.
* Generar reportes.

---

# Controller

```text
AssetsController.php
```

---

# Requests

```text
StoreAssetRequest.php

UpdateAssetRequest.php

AssignAssetRequest.php

AssetMaintenanceRequest.php
```

---

# Resources

```text
AssetResource.php

AssetAssignmentResource.php
```

---

# Policy

```text
AssetsPolicy.php
```

---

# Permisos

```text
assets.view

assets.create

assets.update

assets.delete

assets.assign

assets.maintenance

assets.export
```

---

# Endpoints Web

```http
GET     /assets

GET     /assets/create

POST    /assets

GET     /assets/{id}

PUT     /assets/{id}
```

---

# Endpoints API

```http
GET     /api/v1/assets

POST    /api/v1/assets

PUT     /api/v1/assets/{id}

GET     /api/v1/assets/assignments

POST    /api/v1/assets/assignments
```

---

# Workflow

## Activo

```text
Purchase
 ↓
Registration
 ↓
Assignment
 ↓
Maintenance
 ↓
Retirement
```

---

## Mantenimiento

```text
Scheduled
 ↓
In Progress
 ↓
Completed
```

---

# Integraciones

## Human Resources

```text
Employee
 ↓
Asset Assignment
```

---

## Accounting

```text
Asset
 ↓
Depreciation
 ↓
Journal Entry
```

---

## Procurement

```text
Purchase Order
 ↓
Asset Creation
```

---

## Notifications

```text
Maintenance Due

Assignment Created

Asset Returned
```

---

# Dashboard

Widgets:

```text
Total Assets

Assigned Assets

Maintenance Due

Asset Value

Depreciation

Retired Assets
```

---

# Analytics

KPIs:

```text
Asset Count

Asset Value

Depreciation Value

Maintenance Cost

Utilization Rate

Replacement Rate
```

---

# Auditoría

Eventos:

```text
Asset Created

Asset Assigned

Asset Returned

Maintenance Registered

Asset Retired
```

---

# Testing

## Unit Tests

```text
AssetRepositoryTest

AssetsServiceTest

AssetMaintenanceTest
```

---

## Feature Tests

```text
CreateAssetTest

AssignAssetTest

MaintenanceWorkflowTest
```

---

# Reglas de Negocio

## Regla 1

Todo activo pertenece a una empresa.

---

## Regla 2

Todo activo debe tener categoría.

---

## Regla 3

Un activo en mantenimiento no puede asignarse.

---

## Regla 4

Toda asignación debe quedar auditada.

---

## Regla 5

Toda consulta debe respetar company_id.

---

# KPI del Módulo

* Activos totales.
* Activos asignados.
* Valor patrimonial.
* Coste de mantenimiento.
* Depreciación acumulada.
* Activos retirados.

---

# Resultado Esperado

El módulo Assets permitirá administrar de forma integral los activos empresariales de IAtechs Pro, proporcionando control patrimonial, seguimiento operativo, mantenimiento preventivo, depreciación financiera e integración completa con Recursos Humanos, Compras, Contabilidad y Reportes.
