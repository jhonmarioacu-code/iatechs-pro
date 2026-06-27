# GitHub Environments and Secrets

## Objetivo
Estandarizar la configuracion de `Environments`, `Secrets` y controles de ejecucion para despliegues seguros.

## Estado al 2026-06-27

Configuracion validada en produccion con despliegue automatizado y verificacion estricta de observabilidad en verde.

## Environments requeridos
- `staging` (opcional pero recomendado)
- `production` (obligatorio)

## Configuracion de `production`
1. Ir a `Repository Settings -> Environments -> New environment`.
2. Nombre: `production`.
3. Activar `Required reviewers` (minimo 1 responsable).
4. Activar `Wait timer` si se desea ventana de validacion.
5. Restringir ramas permitidas (`main`).

## Secrets por environment (`production`)
Base de despliegue:
- `SSH_HOST`: IP o host del servidor.
- `SSH_PORT`: puerto SSH (normalmente `22`).
- `SSH_USER`: usuario de despliegue.
- `SSH_PRIVATE_KEY`: llave privada para acceso remoto.
- `DEPLOY_PATH`: ruta del proyecto en servidor.

Observabilidad automatizada (requeridos cuando `provision_observability_stack=true`):
- `OBS_EXPORTER_TOKEN`: token del endpoint `/api/metrics/prometheus`.
- `OBS_ALERTS_SLACK_WEBHOOK_URL`: webhook para Alertmanager y alertas operativas.

Observabilidad automatizada (opcionales recomendados):
- `OBS_EXPORTER_ALLOWED_IPS`: allowlist del exporter (CSV/CIDR). Recomendado: `127.0.0.1,::1,172.16.0.0/12`.
- `PROMETHEUS_RETENTION_TIME`: retencion TSDB (ejemplo `15d`).
- `GRAFANA_ADMIN_USER`: usuario admin Grafana.
- `GRAFANA_ADMIN_PASSWORD`: password admin Grafana.

## Variables recomendadas (environment variables)
- `APP_NAME`
- `APP_ENV=production`
- `HEALTHCHECK_URL=http://127.0.0.1/health`

## Reglas de seguridad
- No usar cuenta `root` para deploy.
- Llaves SSH con passphrase cuando sea posible.
- Rotacion de llaves cada 90 dias.
- Revocar acceso al retirar colaboradores.
- No imprimir secretos en logs de workflow.
- Si se habilita `validate_prometheus_stack=true`, el host debe permitir `docker info` y `docker compose` al usuario `SSH_USER`.

## Validacion de acceso
Antes de habilitar deploy automatico:
```bash
ssh -p <SSH_PORT> <SSH_USER>@<SSH_HOST>
cd <DEPLOY_PATH>
git status
```

## Gobernanza de cambios
- Cada cambio de secretos debe registrarse en bitacora interna.
- Deploy en `production` debe requerir PR mergeado + aprobacion de environment.
- Prohibido deploy directo desde ramas feature.

## Checklist de configuracion inicial
- [x] Environment `production` creado.
- [x] Reviewers obligatorios configurados.
- [x] Secretos base de despliegue cargados y verificados.
- [x] Secretos de observabilidad cargados para `provision_observability_stack=true`.
- [x] Runner puede conectarse al host remoto.
- [x] Healthcheck URL validada.
- [x] Permisos minimos aplicados.

## Evidencia de cierre

- Workflow `Deploy` ejecutado en verde con `target_env=production`.
- Provision de secretos de observabilidad aplicado y validado en pipeline.
- `validate_prometheus_stack=true` en verde con Prometheus/Alertmanager/Grafana healthy.
- Seguridad de secretos gestionada via GitHub Environments sin exponer credenciales en repositorio.
- Referencia operativa: `docs/operations/27-Production-GoLive-Evidence-2026-06-27.md`.

