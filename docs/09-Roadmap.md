# IAtechs Pro - Roadmap y Estado Real

Fecha de actualizacion: 2026-06-27

## Objetivo

Mantener sincronizado el roadmap estrategico con el estado real del codigo, QA, seguridad y despliegue productivo.

## Vista por fases

1. Fase 1 - Core SaaS: implementada y estable (multi-tenant, companies, branches, users, plans, subscriptions, auth, RBAC).
2. Fase 2 - CRM: implementacion operativa estable (leads, opportunities, notas y actividades).
3. Fase 3 - Operaciones tecnicas: implementacion operativa estable (tickets, diagnostics, quotes, repairs, warranties).
4. Fase 4 - Inventario y compras: implementacion funcional estable (products, inventory movements, suppliers, purchase orders, goods receipts).
5. Fase 5 - Finanzas: implementacion funcional estable (invoices, invoice items, payments, billing, accounting base).
6. Fase 6 - Portal cliente y portales de rol: implementacion operativa (admin/company/technician/customer).
7. Fase 7 - IA y conocimiento: implementacion operativa (AI assistant, providers, conversaciones, KB).
8. Fase 8 - Analytics y BI: implementacion operativa (analytics, reports, business intelligence).
9. Fase 9 - Hardening enterprise: **cerrada** (auditoria, seguridad, permisos, pruebas, runbooks, deploy estricto, observabilidad).
10. Fase 10 - Expansion (marketplace, academia, mobile): pendiente.

## Actualizacion 2026-06-27

- Deploy automatizado en produccion validado en modo estricto.
- Observabilidad externa operativa: Prometheus, Alertmanager, Grafana y exporter protegido.
- Security gates y prechecks de despliegue en verde.
- Dashboard admin redisenado con estilo premium sin cambiar estructura ni logica de negocio.

## Estado actual del proyecto

- Etapa actual: **Produccion final validada**.
- Arquitectura: DDD + multi-tenant + policies + service/repository + form requests + resources.
- Cobertura funcional: flujos core de negocio implementados y verificados.
- CI/CD: pipeline activo con seguridad, despliegue y rollback.
- Operaciones: runbooks y evidencia de go-live actualizados.

## Evidencia de madurez (baseline actual)

- Suite funcional y de seguridad: `101 passed (614 assertions)` (`composer test`).
- Gate de release: `Release gate passed` (`php artisan iatechs:gate-release`).
- Auditoria de arquitectura: `Architecture audit passed` (`php artisan iatechs:audit-architecture` + phpstan en verde).
- Seguridad de dependencias:
  - `composer audit --locked`: sin vulnerabilidades.
  - `npm audit --omit=dev --audit-level=high`: `found 0 vulnerabilities`.
- Evidencia operativa de produccion:
  - `RELEASE_READY.md`
  - `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`

## Brechas bloqueantes para produccion final

No hay brechas bloqueantes abiertas al 2026-06-27.

## Deuda tecnica no bloqueante

1. Continuar extraccion progresiva de orquestacion del `PortalController` hacia servicios de dominio.
2. Continuar evolucion de Fase 10 (expansion funcional y canales complementarios).

## Criterio de salida a produccion

Cumplido al 2026-06-27 con evidencia de gates, deploy, rollback y observabilidad estricta en verde.

## Proxima etapa inmediata

**Inicio de Fase 10 - Expansion controlada**:

1. Priorizacion de funcionalidades de marketplace/academia/mobile.
2. Plan de capacidad y SLO por modulo de mayor trafico.
3. Ciclo de mejora continua sobre UX y eficiencia operativa.

