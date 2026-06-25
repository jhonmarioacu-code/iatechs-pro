# IAtechs Pro

# Module Specification

## 38-DocumentManagement

---

# Objetivo

Administrar la documentación empresarial de forma centralizada, segura y auditable, permitiendo control de versiones, aprobaciones, firmas digitales, retención documental y acceso controlado por roles y empresas.

---

# Nombre Técnico

```text
DocumentManagement
```

---

# Descripción

El módulo Document Management permite gestionar el ciclo de vida completo de documentos corporativos.

Permite:

* Crear documentos.
* Organizar carpetas.
* Controlar versiones.
* Gestionar aprobaciones.
* Gestionar firmas digitales.
* Controlar acceso.
* Aplicar políticas de retención.
* Mantener auditoría documental.

---

# Componentes

## Documents

Documentos.

---

## Document Folders

Carpetas.

---

## Document Versions

Versiones.

---

## Document Approvals

Aprobaciones.

---

## Digital Signatures

Firmas digitales.

---

## Retention Policies

Políticas de retención.

---

# Tablas

```text
documents

document_folders

document_versions

document_approvals

document_signatures

document_retention_policies
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Document Manager

Gestión documental.

---

## Manager

Aprobación.

---

## Employee

Acceso según permisos.

---

# Estados Documento

```text
draft

review

approved

published

archived

expired
```

---

# Relaciones

## Folder → Documents

```text
1:N
```

---

## Document → Versions

```text
1:N
```

---

## Document → Approvals

```text
1:N
```

---

## Document → Signatures

```text
1:N
```

---

# Campos Principales

## Document

```text
id

company_id

folder_id

owner_id

title

description

document_type

status

published_at
```

---

## Document Folder

```text
id

company_id

parent_id

name

description
```

---

## Document Version

```text
id

document_id

version

file_path

uploaded_by

created_at
```

---

## Document Approval

```text
id

document_id

approved_by

status

comments

approved_at
```

---

## Document Signature

```text
id

document_id

signed_by

signed_at

signature_hash
```

---

# Modelos

## Document

```text
app/Domains/DocumentManagement/Models/Document.php
```

---

## DocumentFolder

```text
app/Domains/DocumentManagement/Models/DocumentFolder.php
```

---

## DocumentVersion

```text
app/Domains/DocumentManagement/Models/DocumentVersion.php
```

---

## DocumentApproval

```text
app/Domains/DocumentManagement/Models/DocumentApproval.php
```

---

## DocumentSignature

```text
app/Domains/DocumentManagement/Models/DocumentSignature.php
```

---

# Repositories

```text
DocumentRepository.php

DocumentVersionRepository.php
```

---

# Service

```text
DocumentManagementService.php
```

---

# Responsabilidades

* Gestionar documentos.
* Gestionar carpetas.
* Gestionar versiones.
* Gestionar aprobaciones.
* Gestionar firmas digitales.
* Aplicar retención documental.
* Garantizar trazabilidad.

---

# Controller

```text
DocumentManagementController.php
```

---

# Requests

```text
StoreDocumentRequest.php

UpdateDocumentRequest.php

ApproveDocumentRequest.php

SignDocumentRequest.php
```

---

# Resources

```text
DocumentResource.php

DocumentVersionResource.php
```

---

# Policy

```text
DocumentManagementPolicy.php
```

---

# Permisos

```text
documents.view

documents.create

documents.update

documents.delete

documents.approve

documents.sign

documents.export
```

---

# Endpoints Web

```http
GET     /documents

GET     /documents/create

POST    /documents

GET     /documents/{id}

PUT     /documents/{id}
```

---

# Endpoints API

```http
GET     /api/v1/documents

POST    /api/v1/documents

PUT     /api/v1/documents/{id}

GET     /api/v1/document-folders

POST    /api/v1/document-folders
```

---

# Workflow

## Documento

```text
Draft
 ↓
Review
 ↓
Approval
 ↓
Publication
 ↓
Archive
```

---

## Firma Digital

```text
Document
 ↓
Approval
 ↓
Digital Signature
 ↓
Final Document
```

---

# Integraciones

## Knowledge Base

```text
Document
 ↓
Knowledge Article
```

---

## Human Resources

```text
Policies

Contracts

Employee Documents
```

---

## Procurement

```text
Supplier Contracts

Purchase Documents
```

---

## Compliance

```text
Policies

Audits

Evidence
```

---

# Storage

Estructura:

```text
companies/

├── {company_id}
│   ├── documents
│   ├── contracts
│   ├── policies
│   ├── procedures
│   └── archives
```

---

# Versionado

Ejemplo:

```text
Contract_v1.pdf

Contract_v2.pdf

Contract_v3.pdf
```

---

# Retención Documental

Ejemplos:

```text
Contracts → 10 años

Invoices → 5 años

HR Documents → 10 años

Audit Logs → 5 años
```

---

# Dashboard

Widgets:

```text
Documents

Pending Approvals

Pending Signatures

Published Documents

Archived Documents
```

---

# Analytics

KPIs:

```text
Documents Created

Documents Approved

Documents Signed

Documents Archived

Approval Time

Version Count
```

---

# Auditoría

Eventos:

```text
Document Created

Document Updated

Document Approved

Document Signed

Document Archived

Version Uploaded
```

---

# Testing

## Unit Tests

```text
DocumentRepositoryTest

DocumentVersionTest

DocumentManagementServiceTest
```

---

## Feature Tests

```text
DocumentApprovalTest

DocumentSignatureTest

DocumentVersioningTest
```

---

# Reglas de Negocio

## Regla 1

Todo documento pertenece a una empresa.

---

## Regla 2

Toda versión debe quedar registrada.

---

## Regla 3

Las firmas digitales son inmutables.

---

## Regla 4

Toda aprobación debe quedar auditada.

---

## Regla 5

Toda consulta debe respetar company_id.

---

# KPI del Módulo

* Documentos creados.
* Documentos aprobados.
* Documentos firmados.
* Tiempo promedio de aprobación.
* Versiones registradas.
* Documentos archivados.

---

# Resultado Esperado

El módulo Document Management permitirá administrar toda la documentación corporativa de IAtechs Pro con control de versiones, aprobaciones, firmas digitales, auditoría completa y cumplimiento empresarial, integrándose con Recursos Humanos, Compras, Compliance, Knowledge Base e Inteligencia Artificial.
