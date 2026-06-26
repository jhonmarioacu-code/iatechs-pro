# IAtechs Pro - Roadmap y Estado Real

Fecha de actualizacion: 2026-06-25

## Objetivo

Mantener sincronizado el roadmap estrategico con el estado real del codigo, QA y despliegue.

## Vista por fases

1. Fase 1 - Core SaaS: implementada en base (multi-tenant, companies, branches, users, plans, subscriptions, auth, RBAC).
2. Fase 2 - CRM: implementacion operativa inicial (leads, opportunities, notas y actividades).
3. Fase 3 - Operaciones tecnicas: implementacion operativa (tickets, diagnostics, quotes, repairs, warranties).
4. Fase 4 - Inventario y compras: implementacion funcional base (products, inventory movements, suppliers, purchase orders, goods receipts).
5. Fase 5 - Finanzas: implementacion funcional base (invoices, invoice items, payments, billing, accounting base).
6. Fase 6 - Portal cliente y portales de rol: implementacion operativa (admin/company/technician/customer).
7. Fase 7 - IA y conocimiento: implementacion inicial (AI assistant, providers, conversaciones, KB).
8. Fase 8 - Analytics y BI: implementacion inicial (analytics, reports, business intelligence).
9. Fase 9 - Hardening enterprise: en curso (auditoria, seguridad, permisos, pruebas, runbooks).
10. Fase 10 - Expansion (marketplace, academia, mobile): pendiente.

## Estado actual del proyecto

- Etapa actual: **Preproduccion tecnica (beta avanzada)**.
- Arquitectura: DDD + multi-tenant + policies + service/repository + form requests + resources.
- Cobertura funcional: flujos core de negocio implementados y probados para escenarios criticos.
- CI/CD: pipeline de CI y workflow de deploy presentes.
- Operaciones: runbooks de release, rollback, monitoring y backup documentados.

## Evidencia de madurez (baseline actual)

- Suite funcional y de seguridad: `69 passed (450 assertions)`.
- Health checks operativos: `/health` y `/api/health`.
- Automatizacion de deploy con rollback.

## Brechas para declarar produccion final

1. Cerrar gates de calidad estaticos en el alcance oficial de release (`composer analyse`).
2. Ejecutar checklist de release en servidor objetivo con evidencia (build, migrate, health, smoke postdeploy).
3. Finalizar matriz formal `rol -> permiso -> ruta` para modulos admin/API.
4. Reducir logica orquestadora en `PortalController` hacia `Services/Actions` de dominio.

## Criterio de salida a produccion

Se declara produccion final solo cuando:

1. `composer analyse` y `composer test` estan en verde en CI.
2. Build frontend de release validado en servidor objetivo.
3. Migraciones aplicadas sin incidentes y rollback probado.
4. Health checks + smoke por portal en verde postdeploy.
5. Checklist de seguridad y permisos firmado para release.

## Proxima etapa inmediata

**Cierre de hardening preproduccion**:

1. Terminar alineacion de permisos por endpoint.
2. Extraer logica de portal hacia capa de dominio.
3. Ejecutar release candidate en entorno objetivo con evidencia completa.

