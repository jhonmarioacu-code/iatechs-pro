# IAtechsPro Documentation Canon

Fecha: 2026-06-26  
Estado: Oficial

Este archivo define la unica ruta oficial para documentar, construir y auditar IAtechsPro.

## Vision oficial

IAtechsPro es una plataforma SaaS Enterprise Multi-Tenant para empresas de servicios tecnicos.  
No es solo un sistema de tickets.

Cada empresa (tenant) administra desde una sola plataforma:

- Clientes y CRM
- Tickets, diagnosticos, reparaciones y ordenes de trabajo
- Inventario, compras, ventas y facturacion
- Reportes, auditoria, automatizaciones e IA
- Portal de cliente y capacidades de marketplace

## Stack oficial

- Backend: PHP 8.4+, Laravel 12
- Arquitectura: DDD + Clean Architecture + SOLID
- Base de datos oficial: PostgreSQL
- Cache, colas, sesiones y realtime: Redis + Horizon
- Frontend fase actual: Blade + Vite + TailwindCSS + Alpine.js
- Infra: Ubuntu 24.04 + Nginx + PHP-FPM + Supervisor

## Track V2 (Target Architecture)

Se define un track de evolucion V2 con Next.js + NestJS para escalar a arquitectura cloud-native enterprise.

- Blueprint V2: `docs/architecture/19-Target-Architecture-V2-Nextjs-Nestjs.md`
- ADR de adopcion: `docs/decisions/0002-target-architecture-v2-nextjs-nestjs.md`

## Bloques canonicos obligatorios

La documentacion oficial se organiza en 4 bloques:

1. Arquitectura
2. Dominio del negocio
3. Implementacion tecnica
4. Gobernanza del proyecto

### 1) Arquitectura (Source of Truth)

- Principal: `docs/architecture/18-Canonical-Architecture-Source-Of-Truth.md`
- Complementario: `docs/architecture/*`
- Objetivo V2: `docs/architecture/19-Target-Architecture-V2-Nextjs-Nestjs.md`

### 2) Dominio del negocio

- Principal: `docs/modules/00-Business-Domain-Map.md`
- Complementario: `docs/modules/*` y `docs/modules-enterprise/*`

### 3) Implementacion tecnica

- Principal: `docs/development/09-Technical-Implementation-Contract.md`
- API: `docs/api/README.md`
- Deployment: `docs/deployment/README.md`
- Complementario: `docs/development/*`, `docs/operations/*`, `DEPLOYMENT.md`

### 4) Gobernanza del proyecto

- Principal: `docs/operations/21-Project-Governance-Contract.md`
- Decisions (ADR): `docs/decisions/README.md`
- Standards: `docs/standards/*`
- Estado operativo: `docs/operations/19-Project-Status-And-Stage.md`
- Auditoria automatica: `docs/operations/22-Architecture-Audit-Runbook.md`
- Gate de release y checklist obligatorio: `docs/operations/23-Release-Gate-And-Delivery-Checklist.md`
- Realtime operativo y autorizacion de canales: `docs/operations/24-Realtime-Broadcast-Runbook.md`
- Observabilidad externa Prometheus/Grafana: `docs/operations/25-Observability-Prometheus-Grafana-Runbook.md`
- Security scanning SCA/SAST/secrets: `docs/operations/26-Security-Scanning-Runbook.md`
- Evidencia de go-live productivo: `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`

## Estructura oficial de carpetas de documentacion

```text
docs/
├── architecture/
├── development/
├── modules/
├── modules-enterprise/
├── api/
├── deployment/
├── decisions/
├── diagrams/
├── operations/
└── standards/
```

## Reglas no negociables

1. PostgreSQL es el motor oficial para produccion.
2. Toda entidad operativa debe aislarse por `company_id`.
3. La logica de negocio vive en `app/Domains/*`.
4. No se permite logica de negocio en controllers.
5. API versionada bajo `routes/api/v1/*`.
6. Todo cambio relevante exige pruebas y actualizacion documental.

## Flujo obligatorio antes de programar

1. Analizar requerimiento y dominios impactados.
2. Revisar arquitectura y contratos canonicos.
3. Disenar cambios (datos, API, seguridad, tenancy).
4. Implementar siguiendo estandares.
5. Escribir/actualizar pruebas.
6. Actualizar documentacion del modulo y decision tecnica cuando aplique.
