# Production Go-Live Evidence

Fecha: 2026-06-27  
Estado: Aprobado

## Objetivo

Registrar evidencia operativa del cierre de release y validacion de produccion final.

## Alcance validado

- Deploy automatizado desde GitHub Actions (`Deploy`).
- Security gates previos al despliegue.
- Health check de aplicacion.
- Observabilidad avanzada de pagos/suscripciones:
  - Prometheus
  - Alertmanager
  - Grafana
  - Exporter protegido por token + allowlist IP
- Verificacion estricta postdeploy (`validate_prometheus_stack=true`).

## Evidencia resumida

1. Despliegue base en `production` en verde.
2. Despliegue estricto de observabilidad en verde.
3. Build backend/frontend en servidor objetivo en verde.
4. `/health` en verde despues de despliegue.
5. Postdeploy checks de observabilidad en verde.

## Controles de seguridad aplicados

- Secretos gestionados via GitHub Environments (`production`).
- No exposicion de secretos en repositorio.
- Endpoint de metricas protegido por token y allowlist.
- Pipeline de seguridad habilitado para release (`security-gates`).

## Controles de resiliencia aplicados

- Backup de `.env` y `storage` antes de actualizar.
- Rollback automatizado ante error.
- Preflight de permisos runtime para evitar drift en archivos versionados.
- Manejo robusto de permisos para build frontend.

## Criterios de salida (checklist)

- [x] `security-gates` en verde.
- [x] `precheck` en verde.
- [x] `deploy` en verde.
- [x] `postcheck` en verde.
- [x] `/health` responde `200`.
- [x] Prometheus target `iatechs_app` en `UP`.
- [x] Alertmanager healthy.
- [x] Grafana healthy.

## Resultado final

**Release aprobado para produccion final**.

Referencias:

- `RELEASE_READY.md`
- `docs/operations/13-Deployment-Automation-GitHub-Actions.md`
- `docs/operations/17-Deploy-Workflow-Runbook.md`
- `docs/operations/25-Observability-Prometheus-Grafana-Runbook.md`
