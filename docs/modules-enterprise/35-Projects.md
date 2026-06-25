# IAtechs Pro

# Module Specification

## 35-Projects

---

# Objetivo

Administrar proyectos empresariales, tareas, hitos, equipos de trabajo, tiempos y costos para garantizar la correcta planificación, ejecución y seguimiento de iniciativas internas y externas.

---

# Nombre Técnico

```text
Projects
```

---

# Descripción

El módulo Projects permite gestionar proyectos completos dentro de la organización.

Permite:

* Crear proyectos.
* Gestionar tareas.
* Gestionar hitos.
* Asignar recursos.
* Controlar tiempos.
* Controlar presupuestos.
* Gestionar equipos.
* Monitorear avance.

---

# Componentes

## Projects

Proyectos.

---

## Tasks

Tareas.

---

## Milestones

Hitos.

---

## Project Members

Integrantes.

---

## Timesheets

Registro de tiempo.

---

# Tablas

```text
projects

tasks

milestones

project_members

timesheets
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## Project Manager

Gestión completa.

---

## Team Member

Acceso según asignación.

---

# Estados Proyecto

```text
draft

planning

active

on_hold

completed

cancelled
```

---

# Estados Tarea

```text
pending

in_progress

review

completed

cancelled
```

---

# Relaciones

## Project → Tasks

```text
1:N
```

---

## Project → Milestones

```text
1:N
```

---

## Project → Members

```text
1:N
```

---

## Employee → Timesheets

```text
1:N
```

---

# Campos Principales

## Project

```text
id

company_id

manager_id

name

description

start_date

end_date

budget

status
```

---

## Task

```text
id

project_id

assigned_to

title

description

priority

status

due_date
```

---

## Milestone

```text
id

project_id

name

description

due_date

status
```

---

## Timesheet

```text
id

project_id

employee_id

date

hours

description
```

---

# Modelos

## Project

```text
app/Domains/Projects/Models/Project.php
```

---

## Task

```text
app/Domains/Projects/Models/Task.php
```

---

## Milestone

```text
app/Domains/Projects/Models/Milestone.php
```

---

## ProjectMember

```text
app/Domains/Projects/Models/ProjectMember.php
```

---

## Timesheet

```text
app/Domains/Projects/Models/Timesheet.php
```

---

# Repositories

```text
ProjectRepository.php

TaskRepository.php
```

---

# Service

```text
ProjectsService.php
```

---

# Responsabilidades

* Crear proyectos.
* Gestionar tareas.
* Gestionar hitos.
* Registrar tiempos.
* Calcular avance.
* Gestionar presupuestos.

---

# Controller

```text
ProjectsController.php
```

---

# Requests

```text
StoreProjectRequest.php

UpdateProjectRequest.php

StoreTaskRequest.php

UpdateTaskRequest.php
```

---

# Resources

```text
ProjectResource.php

TaskResource.php
```

---

# Policy

```text
ProjectsPolicy.php
```

---

# Permisos

```text
projects.view

projects.create

projects.update

projects.delete

projects.manage_tasks

projects.manage_members

projects.export
```

---

# Endpoints Web

```http
GET     /projects

GET     /projects/create

POST    /projects

GET     /projects/{id}

PUT     /projects/{id}
```

---

# Endpoints API

```http
GET     /api/v1/projects

POST    /api/v1/projects

PUT     /api/v1/projects/{id}

GET     /api/v1/tasks

POST    /api/v1/tasks

PUT     /api/v1/tasks/{id}
```

---

# Workflow

## Proyecto

```text
Draft
 ↓
Planning
 ↓
Active
 ↓
Completed
```

---

## Tarea

```text
Pending
 ↓
In Progress
 ↓
Review
 ↓
Completed
```

---

# Integraciones

## Human Resources

```text
Employee
 ↓
Project Assignment
```

---

## Accounting

```text
Project
 ↓
Cost Tracking
```

---

## CRM

```text
Opportunity Won
 ↓
Project Creation
```

---

## AI Assistant

Funciones:

```text
Task Suggestions

Risk Analysis

Project Forecasting

Productivity Insights
```

---

# Dashboard

Widgets:

```text
Projects

Tasks

Milestones

Hours Logged

Budget Usage

Project Progress
```

---

# Analytics

KPIs:

```text
Active Projects

Completed Projects

Project Success Rate

Budget Utilization

Hours Logged

Task Completion Rate
```

---

# Auditoría

Eventos:

```text
Project Created

Project Updated

Task Assigned

Task Completed

Milestone Completed

Project Closed
```

---

# Testing

## Unit Tests

```text
ProjectRepositoryTest

TaskRepositoryTest

ProjectsServiceTest
```

---

## Feature Tests

```text
CreateProjectTest

TaskWorkflowTest

ProjectProgressTest
```

---

# Reglas de Negocio

## Regla 1

Todo proyecto pertenece a una empresa.

---

## Regla 2

Todo proyecto debe tener responsable.

---

## Regla 3

Toda tarea debe pertenecer a un proyecto.

---

## Regla 4

Todo registro debe respetar company_id.

---

## Regla 5

Los tiempos registrados afectan métricas del proyecto.

---

# KPI del Módulo

* Proyectos activos.
* Proyectos completados.
* Avance promedio.
* Horas registradas.
* Presupuesto consumido.
* Productividad del equipo.

---

# Resultado Esperado

El módulo Projects permitirá gestionar proyectos empresariales de principio a fin dentro de IAtechs Pro, proporcionando planificación, ejecución, seguimiento, control de recursos y análisis de desempeño, integrándose con RRHH, CRM, Contabilidad e Inteligencia Artificial.
