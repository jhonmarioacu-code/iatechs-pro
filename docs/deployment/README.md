# Deployment Documentation

Fecha: 2026-06-26  
Estado: Oficial

## Objetivo

Consolidar guias de despliegue, operacion y release para entornos productivos.

## Stack de infraestructura objetivo

- Ubuntu 24.04
- Nginx
- PHP-FPM 8.4
- Supervisor
- PostgreSQL
- Redis

## Documentos base

- `DEPLOYMENT.md`
- `docs/operations/01-Environment-Setup.md`
- `docs/operations/02-Server-Provisioning.md`
- `docs/operations/10-Release-Checklist.md`
- `docs/operations/12-Post-Deploy-Verification.md`
- `docs/operations/17-Deploy-Workflow-Runbook.md`
- `docs/operations/18-Rollback-Procedure.md`
- `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`

## Reglas clave

1. Produccion usa PostgreSQL como motor oficial.
2. Cache y colas en Redis + Horizon.
3. Verificar `/health` y `/api/health` postdeploy.
4. Mantener rollback documentado y probado.

