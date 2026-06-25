# IAtechs Pro

# Module Specification

## 34-HumanResources

---

# Objetivo

Administrar el capital humano de las empresas registradas en IAtechs Pro mediante la gestión de empleados, departamentos, cargos, asistencia, permisos, vacaciones, nómina, evaluaciones y capacitaciones.

---

# Nombre Técnico

```text
HumanResources
```

---

# Descripción

El módulo Human Resources permite gestionar todo el ciclo de vida de los colaboradores dentro de la empresa.

Permite:

* Gestionar empleados.
* Gestionar departamentos.
* Gestionar cargos.
* Controlar asistencia.
* Gestionar vacaciones.
* Gestionar permisos.
* Administrar nómina.
* Gestionar evaluaciones.
* Gestionar capacitaciones.

---

# Componentes

## Employees

Empleados de la empresa.

---

## Departments

Departamentos organizacionales.

---

## Positions

Cargos laborales.

---

## Attendance

Control de asistencia.

---

## Leave Requests

Vacaciones y permisos.

---

## Payroll

Nómina.

---

## Performance Reviews

Evaluaciones de desempeño.

---

## Training

Capacitaciones.

---

# Tablas

```text
employees

departments

positions

attendances

leave_requests

payrolls

performance_reviews

trainings
```

---

# Roles con Acceso

## Super Admin

Acceso total.

---

## Owner

Acceso completo.

---

## HR Manager

Gestión completa de RRHH.

---

## Manager

Consulta y aprobación.

---

## Employee

Acceso a información propia.

---

# Estados Empleado

```text
active

inactive

suspended

terminated
```

---

# Tipos de Permiso

```text
vacation

sick_leave

personal_leave

maternity_leave

paternity_leave

unpaid_leave
```

---

# Relaciones

## Department → Employees

```text
1:N
```

---

## Position → Employees

```text
1:N
```

---

## Employee → Attendance

```text
1:N
```

---

## Employee → Leave Requests

```text
1:N
```

---

## Employee → Payroll

```text
1:N
```

---

## Employee → Performance Reviews

```text
1:N
```

---

# Campos Principales

## Employee

```text
id

company_id

department_id

position_id

employee_code

first_name

last_name

email

phone

hire_date

salary

status
```

---

## Department

```text
id

company_id

name

description
```

---

## Position

```text
id

company_id

department_id

name

description
```

---

## Attendance

```text
id

employee_id

date

check_in

check_out

hours_worked
```

---

## Leave Request

```text
id

employee_id

type

start_date

end_date

reason

status
```

---

## Payroll

```text
id

employee_id

period

gross_salary

deductions

net_salary
```

---

# Modelos

## Employee

```text
app/Domains/HumanResources/Models/Employee.php
```

---

## Department

```text
app/Domains/HumanResources/Models/Department.php
```

---

## Position

```text
app/Domains/HumanResources/Models/Position.php
```

---

## Attendance

```text
app/Domains/HumanResources/Models/Attendance.php
```

---

## LeaveRequest

```text
app/Domains/HumanResources/Models/LeaveRequest.php
```

---

## Payroll

```text
app/Domains/HumanResources/Models/Payroll.php
```

---

# Repositories

```text
EmployeeRepository.php

DepartmentRepository.php

PayrollRepository.php
```

---

# Service

```text
HumanResourcesService.php
```

---

# Responsabilidades

* Gestionar empleados.
* Gestionar asistencia.
* Gestionar vacaciones.
* Gestionar permisos.
* Generar nómina.
* Gestionar evaluaciones.
* Gestionar capacitaciones.

---

# Controller

```text
HumanResourcesController.php
```

---

# Requests

```text
StoreEmployeeRequest.php

UpdateEmployeeRequest.php

StoreLeaveRequest.php

StorePayrollRequest.php
```

---

# Resources

```text
EmployeeResource.php

PayrollResource.php
```

---

# Policy

```text
HumanResourcesPolicy.php
```

---

# Permisos

```text
hr.view

hr.create

hr.update

hr.delete

hr.payroll

hr.approve_leave

hr.export
```

---

# Endpoints Web

```http
GET     /human-resources

GET     /employees

POST    /employees

GET     /payroll

POST    /payroll
```

---

# Endpoints API

```http
GET     /api/v1/employees

POST    /api/v1/employees

PUT     /api/v1/employees/{id}

GET     /api/v1/payroll

POST    /api/v1/payroll
```

---

# Workflow

## Empleado

```text
Recruitment
 ↓
Hiring
 ↓
Onboarding
 ↓
Active
 ↓
Termination
```

---

## Vacaciones

```text
Request
 ↓
Manager Approval
 ↓
HR Approval
 ↓
Approved
```

---

## Nómina

```text
Attendance
 ↓
Payroll Calculation
 ↓
Review
 ↓
Payment
```

---

# Integraciones

## Accounting

```text
Payroll
 ↓
Journal Entry
```

---

## Notifications

```text
Leave Approved

Payroll Generated

Review Scheduled
```

---

## AI Assistant

Funciones:

```text
HR Analytics

Turnover Analysis

Payroll Insights

Performance Insights
```

---

# Dashboard

Widgets:

```text
Employees

Attendance

Leave Requests

Payroll

Performance

Training
```

---

# Analytics

KPIs:

```text
Employee Count

Attendance Rate

Turnover Rate

Payroll Cost

Training Hours

Performance Score
```

---

# Auditoría

Eventos:

```text
Employee Created

Employee Updated

Leave Approved

Payroll Generated

Review Completed
```

---

# Testing

## Unit Tests

```text
EmployeeRepositoryTest

PayrollRepositoryTest

HumanResourcesServiceTest
```

---

## Feature Tests

```text
CreateEmployeeTest

PayrollGenerationTest

LeaveApprovalTest
```

---

# Reglas de Negocio

## Regla 1

Todo empleado pertenece a una empresa.

---

## Regla 2

Todo empleado debe pertenecer a un departamento.

---

## Regla 3

Toda nómina debe estar asociada a un período.

---

## Regla 4

Toda consulta debe respetar company_id.

---

## Regla 5

Las vacaciones requieren aprobación.

---

# KPI del Módulo

* Total empleados.
* Empleados activos.
* Rotación de personal.
* Coste de nómina.
* Ausentismo.
* Horas de capacitación.

---

# Resultado Esperado

El módulo Human Resources permitirá administrar integralmente el recurso humano de IAtechs Pro, proporcionando control sobre empleados, asistencia, vacaciones, nómina y desempeño, con integración nativa a Contabilidad, Reportes, Notificaciones e Inteligencia Artificial.
