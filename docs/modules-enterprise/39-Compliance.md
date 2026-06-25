# IAtechs Pro

# Module Specification

## 39-Compliance

---

# Objetivo

Administrar el cumplimiento normativo, auditorías, riesgos, controles internos, incidentes y evidencias corporativas dentro de IAtechs Pro, garantizando trazabilidad, gobierno corporativo y cumplimiento de estándares empresariales.

---

# Nombre Técnico

```text
Compliance
```

---

# Descripción

El módulo Compliance permite gestionar las actividades relacionadas con cumplimiento regulatorio, gestión de riesgos y auditoría interna.

Permite:

* Gestionar políticas corporativas.
* Gestionar riesgos.
* Gestionar controles internos.
* Gestionar auditorías.
* Gestionar hallazgos.
* Gestionar incidentes.
* Gestionar evidencias.
* Monitorear cumplimiento.

---

# Componentes

## Compliance Policies

Políticas corporativas.

---

## Risks

Riesgos empresariales.

---

## Controls

Controles internos.

---

## Audits

Auditorías.

---

## Findings

Hallazgos.

---

## Incidents

Incidentes.

---

## Evidences

Evidencias.

---

# Tablas

```text
compliance_policies

risks

controls

audits

audit_findings

incidents

evidences
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Compliance Manager

Gestión completa.

---

## Auditor

Gestión de auditorías.

---

## Manager

Consulta y seguimiento.

---

# Estados Riesgo

```text
identified

evaluated

mitigated

accepted

closed
```

---

# Estados Auditoría

```text
planned

in_progress

completed

closed
```

---

# Estados Incidente

```text
open

investigating

resolved

closed
```

---

# Relaciones

## Risk → Controls

```text
1:N
```

---

## Audit → Findings

```text
1:N
```

---

## Audit → Evidences

```text
1:N
```

---

## Incident → Evidences

```text
1:N
```

---

# Campos Principales

## Compliance Policy

```text
id

company_id

title

description

version

status

effective_date
```

---

## Risk

```text
id

company_id

name

description

impact

probability

risk_level

status
```

---

## Control

```text
id

risk_id

name

description

owner_id

status
```

---

## Audit

```text
id

company_id

audit_number

title

scope

start_date

end_date

status
```

---

## Audit Finding

```text
id

audit_id

title

description

severity

status
```

---

## Incident

```text
id

company_id

incident_number

title

description

severity

status
```

---

# Modelos

## CompliancePolicy

```text
app/Domains/Compliance/Models/CompliancePolicy.php
```

---

## Risk

```text
app/Domains/Compliance/Models/Risk.php
```

---

## Control

```text
app/Domains/Compliance/Models/Control.php
```

---

## Audit

```text
app/Domains/Compliance/Models/Audit.php
```

---

## AuditFinding

```text
app/Domains/Compliance/Models/AuditFinding.php
```

---

## Incident

```text
app/Domains/Compliance/Models/Incident.php
```

---

# Repositories

```text
ComplianceRepository.php

RiskRepository.php

AuditRepository.php
```

---

# Service

```text
ComplianceService.php
```

---

# Responsabilidades

* Gestionar riesgos.
* Gestionar auditorías.
* Gestionar controles.
* Gestionar incidentes.
* Gestionar evidencias.
* Monitorear cumplimiento.
* Generar reportes regulatorios.

---

# Controller

```text
ComplianceController.php
```

---

# Requests

```text
StoreRiskRequest.php

UpdateRiskRequest.php

StoreAuditRequest.php

StoreIncidentRequest.php
```

---

# Resources

```text
RiskResource.php

AuditResource.php
```

---

# Policy

```text
CompliancePolicy.php
```

---

# Permisos

```text
compliance.view

compliance.create

compliance.update

compliance.delete

compliance.audit

compliance.risk

compliance.export
```

---

# Endpoints Web

```http
GET     /compliance

GET     /compliance/risks

POST    /compliance/risks

GET     /compliance/audits

POST    /compliance/audits
```

---

# Endpoints API

```http
GET     /api/v1/compliance/risks

POST    /api/v1/compliance/risks

GET     /api/v1/compliance/audits

POST    /api/v1/compliance/audits

GET     /api/v1/compliance/incidents
```

---

# Workflow

## Riesgo

```text
Identification
 ↓
Evaluation
 ↓
Mitigation
 ↓
Monitoring
 ↓
Closure
```

---

## Auditoría

```text
Planning
 ↓
Execution
 ↓
Findings
 ↓
Corrective Actions
 ↓
Closure
```

---

## Incidente

```text
Reported
 ↓
Investigation
 ↓
Resolution
 ↓
Closure
```

---

# Integraciones

## Document Management

```text
Policies

Procedures

Evidence Files
```

---

## Human Resources

```text
Employee Compliance

Training Records
```

---

## Audit Logs

```text
Compliance Events
```

---

## AI Assistant

Funciones:

```text
Risk Analysis

Compliance Insights

Audit Recommendations

Incident Analysis
```

---

# Dashboard

Widgets:

```text
Open Risks

Active Audits

Open Incidents

Compliance Score

Pending Actions

Audit Findings
```

---

# Analytics

KPIs:

```text
Compliance Score

Open Risks

Risk Exposure

Audit Completion Rate

Incident Resolution Time

Corrective Actions
```

---

# Auditoría

Eventos:

```text
Risk Created

Risk Updated

Audit Created

Audit Closed

Incident Reported

Corrective Action Completed
```

---

# Testing

## Unit Tests

```text
RiskRepositoryTest

AuditRepositoryTest

ComplianceServiceTest
```

---

## Feature Tests

```text
RiskWorkflowTest

AuditWorkflowTest

IncidentManagementTest
```

---

# Reglas de Negocio

## Regla 1

Todo riesgo pertenece a una empresa.

---

## Regla 2

Toda auditoría debe tener alcance definido.

---

## Regla 3

Todo hallazgo debe generar seguimiento.

---

## Regla 4

Toda evidencia debe quedar almacenada.

---

## Regla 5

Toda consulta debe respetar company_id.

---

# KPI del Módulo

* Riesgos abiertos.
* Riesgos mitigados.
* Auditorías ejecutadas.
* Hallazgos abiertos.
* Incidentes reportados.
* Nivel de cumplimiento.

---

# Estándares Soportados

```text
ISO 9001

ISO 27001

ISO 22301

SOC 2

GDPR

PCI DSS
```

---

# Resultado Esperado

El módulo Compliance permitirá a IAtechs Pro gestionar riesgos, auditorías, controles e incidentes bajo estándares empresariales internacionales, proporcionando trazabilidad completa, evidencias auditables, monitoreo continuo y soporte para cumplimiento regulatorio corporativo.
