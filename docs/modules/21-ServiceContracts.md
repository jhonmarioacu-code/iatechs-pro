# Module Specification

# IAtechs Pro

## Módulo: Service Contracts

---

# Objetivo

Administrar contratos de servicio técnico y mantenimiento entre la empresa y sus clientes, permitiendo controlar cobertura, vigencia, visitas programadas, SLA y renovaciones.

---

# Nombre Técnico

ServiceContracts

---

# Tabla Principal

service_contracts

---

# Dependencias

* Companies
* Branches
* Customers
* Devices
* Invoices
* Users

---

# Descripción

Un contrato de servicio define los términos bajo los cuales IAtechs Pro prestará soporte técnico, mantenimiento preventivo o correctivo a un cliente durante un período determinado.

---

# Estados

## Draft

```text
draft
```

Contrato en preparación.

---

## Active

```text
active
```

Contrato vigente.

---

## Suspended

```text
suspended
```

Contrato suspendido.

---

## Expired

```text
expired
```

Contrato vencido.

---

## Cancelled

```text
cancelled
```

Contrato cancelado.

---

# Tipos de Contrato

## Support

```text
support
```

Soporte técnico.

---

## Preventive Maintenance

```text
preventive
```

Mantenimiento preventivo.

---

## Corrective Maintenance

```text
corrective
```

Mantenimiento correctivo.

---

## Full Service

```text
full_service
```

Cobertura total.

---

## Enterprise SLA

```text
enterprise_sla
```

Contrato corporativo con SLA.

---

# Tabla service_contracts

| Campo                | Tipo      |
| -------------------- | --------- |
| id                   | bigint    |
| company_id           | bigint    |
| branch_id            | bigint    |
| customer_id          | bigint    |
| contract_number      | string    |
| contract_type        | string    |
| start_date           | date      |
| end_date             | date      |
| contract_value       | decimal   |
| sla_hours            | integer   |
| coverage_description | text      |
| status               | string    |
| auto_renew           | boolean   |
| created_by           | bigint    |
| created_at           | timestamp |
| updated_at           | timestamp |

---

# Tabla contract_devices

| Campo               | Tipo      |
| ------------------- | --------- |
| id                  | bigint    |
| service_contract_id | bigint    |
| device_id           | bigint    |
| created_at          | timestamp |

---

# Tabla contract_visits

| Campo               | Tipo      |
| ------------------- | --------- |
| id                  | bigint    |
| service_contract_id | bigint    |
| scheduled_date      | datetime  |
| visit_type          | string    |
| technician_id       | bigint    |
| notes               | text      |
| status              | string    |
| created_at          | timestamp |

---

# Migración Oficial Service Contracts

```php
Schema::create('service_contracts', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('branch_id')
        ->nullable()
        ->constrained('branches');

    $table->foreignId('customer_id')
        ->constrained('customers');

    $table->string('contract_number')
        ->unique();

    $table->enum('contract_type',[
        'support',
        'preventive',
        'corrective',
        'full_service',
        'enterprise_sla'
    ]);

    $table->date('start_date');

    $table->date('end_date');

    $table->decimal('contract_value',12,2)
        ->default(0);

    $table->integer('sla_hours')
        ->nullable();

    $table->text('coverage_description');

    $table->boolean('auto_renew')
        ->default(false);

    $table->enum('status',[
        'draft',
        'active',
        'suspended',
        'expired',
        'cancelled'
    ])->default('draft');

    $table->foreignId('created_by')
        ->constrained('users');

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## Customer

```php
public function customer()
{
    return $this->belongsTo(Customer::class);
}
```

---

## Devices

```php
public function devices()
{
    return $this->belongsToMany(Device::class);
}
```

---

## Visits

```php
public function visits()
{
    return $this->hasMany(ContractVisit::class);
}
```

---

# Modelo

```text
app/Models/ServiceContract.php
```

---

# Repository

```text
app/Repositories/ServiceContractRepository.php
```

---

# Service

```text
app/Services/ServiceContractService.php
```

---

# Responsabilidades

* Crear contratos.
* Gestionar SLA.
* Programar mantenimientos.
* Renovar contratos.
* Suspender contratos.
* Gestionar cobertura.
* Controlar visitas técnicas.

---

# Policy

```text
ServiceContractPolicy
```

---

# Permisos

```text
service_contracts.view
service_contracts.create
service_contracts.update
service_contracts.delete
service_contracts.renew
service_contracts.suspend
service_contracts.export
```

---

# Endpoints Web

```http
GET     /service-contracts
GET     /service-contracts/create
POST    /service-contracts
GET     /service-contracts/{id}
PUT     /service-contracts/{id}
DELETE  /service-contracts/{id}

POST    /service-contracts/{id}/renew
POST    /service-contracts/{id}/suspend
```

---

# Endpoints API

```http
GET     /api/v1/service-contracts
POST    /api/v1/service-contracts
GET     /api/v1/service-contracts/{id}
PUT     /api/v1/service-contracts/{id}
DELETE  /api/v1/service-contracts/{id}
```

---

# Flujo de Negocio

## Crear Contrato

```text
Cliente Empresarial
        ↓
Definir Cobertura
        ↓
Generar Contrato
        ↓
Activo
```

---

## Mantenimiento Programado

```text
Calendario
      ↓
Generar Visita
      ↓
Asignar Técnico
      ↓
Ejecutar Servicio
```

---

## Renovación

```text
Fin de Vigencia
      ↓
Evaluación
      ↓
Renovar
      ↓
Nuevo Período
```

---

# Reglas de Negocio

## Regla 1

Todo contrato debe tener fecha de inicio y fin.

---

## Regla 2

No se podrán registrar servicios fuera de cobertura.

---

## Regla 3

Los contratos vencidos no podrán generar visitas.

---

## Regla 4

Los SLA deben ser medibles.

---

## Regla 5

Toda renovación debe quedar auditada.

---

## Regla 6

Un equipo puede pertenecer a múltiples contratos históricos pero solo a uno activo.

---

# Auditoría

Registrar:

```text
Contrato creado
Contrato activado
Contrato suspendido
Contrato renovado
Contrato vencido
Visita programada
```

---

# Eventos

```text
ServiceContractCreated
ServiceContractActivated
ServiceContractRenewed
ServiceContractExpired
MaintenanceVisitScheduled
```

---

# Jobs

```text
ContractRenewalReminderJob
ContractExpirationJob
GenerateMaintenanceVisitsJob
NotifySlaViolationJob
```

---

# Testing

## Unit Tests

```text
ServiceContractServiceTest
ContractRenewalTest
SlaCalculationTest
```

---

## Feature Tests

```text
CreateContractTest
RenewContractTest
ScheduleVisitTest
ExpireContractTest
```

---

# KPI del Módulo

* Contratos activos.
* Contratos vencidos.
* Ingresos recurrentes.
* Cumplimiento SLA.
* Visitas ejecutadas.
* Renovaciones exitosas.
* Clientes corporativos activos.

---

# Integración con Otros Módulos

```text
Customers
Devices
Tickets
Repairs
Invoices
Payments
Notifications
Analytics
AuditLogs
```

---

# Resultado Esperado

El módulo Service Contracts permitirá que IAtechs Pro ofrezca servicios de mantenimiento y soporte técnico empresarial bajo contratos formales, con control de SLA, ingresos recurrentes, programación automática de visitas y gestión integral del ciclo de vida contractual.
