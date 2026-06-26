# Project Finalization Plan

## Objetivo
Cerrar el proyecto en estado release-ready con trazabilidad técnica, cobertura de riesgos y criterios de aceptación claros.

## Fases de Cierre

## Fase 1: Estabilidad Técnica
- Ejecutar CI completo sin fallos:
  - `php-quality`
  - `frontend-build`
- Verificar `phpstan` en `app` con 0 errores.
- Verificar suite de tests en verde.

## Fase 2: Arquitectura y Cumplimiento
- Confirmar cumplimiento DDD por módulo:
  - `Controller/Service/Repository/Policy/Request/Resource`.
- Confirmar aislamiento multi-tenant en:
  - consultas
  - escritura
  - autorización
- Confirmar matriz RBAC por portal:
  - `super_admin`
  - `company`
  - `technician`
  - `customer`

## Fase 3: Operación y Producción
- Validar contrato de entorno (`11-Production-Env-Contract.md`).
- Validar checklist de release (`10-Release-Checklist.md`).
- Validar verificación post-deploy (`12-Post-Deploy-Verification.md`).
- Habilitar workflow de automatización (`13-Deployment-Automation-GitHub-Actions.md`).

## Fase 4: Auditoría Final
- Ejecutar checklist de auditoría técnica.
- Consolidar brechas críticas y plan de mitigación.
- Definir riesgos residuales aceptados.

## Entregables Finales
- PR de cierre aprobado.
- CI en verde.
- Documentación operativa completa.
- Evidencia de despliegue en entorno objetivo.
- Informe de auditoría firmado por responsable técnico.

## Criterios de Aceptación
- Sin errores críticos abiertos.
- Sin secretos en repositorio.
- Flujos críticos por rol validados.
- Healthcheck `200` estable.
- Recuperación/rollback comprobados.

