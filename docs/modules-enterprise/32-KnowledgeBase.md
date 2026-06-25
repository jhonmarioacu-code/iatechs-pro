# IAtechs Pro

# Module Specification

## 32-KnowledgeBase

---

# Objetivo

Centralizar el conocimiento técnico, comercial y operativo de la empresa mediante artículos, procedimientos, manuales, documentos y recursos internos.

El módulo permitirá construir una Base de Conocimiento empresarial integrada con IA Assistant, Tickets y Clientes.

---

# Nombre Técnico

```text
KnowledgeBase
```

---

# Descripción

El módulo Knowledge Base almacena información estructurada para:

* Procedimientos.
* Manuales.
* Guías.
* Soluciones.
* Preguntas frecuentes.
* Documentación técnica.
* Documentación comercial.
* Artículos internos.

---

# Componentes

## Categories

Categorías de conocimiento.

---

## Articles

Artículos.

---

## Attachments

Archivos asociados.

---

# Tablas

```text
knowledge_categories

knowledge_articles

knowledge_attachments
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Manager

Acceso completo.

---

## Technician

Consulta y creación.

---

## Customer

Consulta limitada.

---

# Categorías

Ejemplos:

```text
Procedimientos

Reparaciones

Inventario

Facturación

CRM

Soporte

IA

Recursos Humanos
```

---

# Estados de Artículo

```text
draft

review

published

archived
```

---

# Relaciones

## Category → Articles

```text
1:N
```

---

## Article → Attachments

```text
1:N
```

---

## Company → Articles

```text
1:N
```

---

# Campos Principales

## KnowledgeCategory

```text
id

company_id

name

description

is_active
```

---

## KnowledgeArticle

```text
id

company_id

category_id

author_id

title

slug

content

status

published_at
```

---

## KnowledgeAttachment

```text
id

article_id

file_name

file_path

mime_type
```

---

# Modelos

## KnowledgeCategory

```text
app/Domains/KnowledgeBase/Models/KnowledgeCategory.php
```

---

## KnowledgeArticle

```text
app/Domains/KnowledgeBase/Models/KnowledgeArticle.php
```

---

## KnowledgeAttachment

```text
app/Domains/KnowledgeBase/Models/KnowledgeAttachment.php
```

---

# Repositories

```text
KnowledgeCategoryRepository.php

KnowledgeArticleRepository.php
```

---

# Service

```text
KnowledgeBaseService.php
```

---

# Responsabilidades

* Crear artículos.
* Editar artículos.
* Publicar artículos.
* Archivar artículos.
* Gestionar categorías.
* Gestionar archivos adjuntos.
* Integrar IA.

---

# Controller

```text
KnowledgeBaseController.php
```

---

# Requests

```text
StoreKnowledgeArticleRequest.php

UpdateKnowledgeArticleRequest.php
```

---

# Resources

```text
KnowledgeArticleResource.php
```

---

# Policy

```text
KnowledgeBasePolicy.php
```

---

# Permisos

```text
knowledge.view

knowledge.create

knowledge.update

knowledge.delete

knowledge.publish

knowledge.archive
```

---

# Endpoints Web

```http
GET     /knowledge-base

GET     /knowledge-base/articles

GET     /knowledge-base/categories

POST    /knowledge-base/articles

PUT     /knowledge-base/articles/{id}
```

---

# Endpoints API

```http
GET     /api/v1/knowledge-base/articles

POST    /api/v1/knowledge-base/articles

PUT     /api/v1/knowledge-base/articles/{id}

DELETE  /api/v1/knowledge-base/articles/{id}
```

---

# Workflow

## Artículo

```text
Draft
 ↓
Review
 ↓
Published
 ↓
Archived
```

---

# Integraciones

## Tickets

```text
Ticket
 ↓
Suggested Article
```

---

## AI Assistant

```text
Pregunta
 ↓
Knowledge Base
 ↓
Respuesta
```

---

## Customers

```text
Portal Cliente
 ↓
Knowledge Base
```

---

# Dashboard

Widgets:

```text
Artículos

Categorías

Documentos

Consultas

Artículos Más Vistos
```

---

# Analytics

KPIs:

```text
Artículos Publicados

Artículos Archivados

Consultas

Búsquedas

Documentos Adjuntos

Uso IA
```

---

# Storage

Ubicación:

```text
companies/{company_id}/knowledge-base/
```

---

# Búsqueda

Soportar:

```text
Título

Contenido

Categoría

Etiquetas
```

---

# AI Integration

Funciones:

```text
Knowledge Search

Context Retrieval

Suggested Articles

AI Answers
```

---

# Auditoría

Eventos:

```text
Article Created

Article Updated

Article Published

Article Archived

Attachment Uploaded

Attachment Deleted
```

---

# Testing

## Unit Tests

```text
KnowledgeBaseServiceTest

KnowledgeArticleRepositoryTest

KnowledgeCategoryRepositoryTest
```

---

## Feature Tests

```text
CreateArticleTest

PublishArticleTest

KnowledgeSearchTest
```

---

# Reglas de Negocio

## Regla 1

Todo artículo pertenece a una empresa.

---

## Regla 2

Todo artículo debe pertenecer a una categoría.

---

## Regla 3

Solo artículos publicados pueden ser visibles para clientes.

---

## Regla 4

Toda consulta debe respetar company_id.

---

## Regla 5

La IA únicamente podrá consultar artículos de la empresa activa.

---

# KPI del Módulo

* Artículos publicados.
* Artículos archivados.
* Categorías activas.
* Consultas realizadas.
* Artículos más consultados.
* Uso por IA.

---

# Resultado Esperado

El módulo Knowledge Base permitirá centralizar el conocimiento corporativo de IAtechs Pro, mejorando la productividad, reduciendo tiempos de soporte, potenciando la inteligencia artificial y proporcionando una fuente única de información para empleados, técnicos y clientes.
