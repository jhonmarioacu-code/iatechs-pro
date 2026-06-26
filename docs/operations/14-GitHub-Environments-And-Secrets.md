# GitHub Environments and Secrets

## Objetivo
Estandarizar la configuración de `Environments`, `Secrets` y controles de ejecución para despliegues seguros.

## Environments Requeridos
- `staging` (opcional pero recomendado)
- `production` (obligatorio)

## Configuración de `production`
1. Ir a: `Repository Settings -> Environments -> New environment`.
2. Nombre: `production`.
3. Activar `Required reviewers` (mínimo 1 responsable).
4. Activar `Wait timer` si se desea ventana de validación.
5. Restringir ramas permitidas (`main`).

## Secrets por Environment (production)
- `SSH_HOST`: IP o host del servidor.
- `SSH_PORT`: puerto SSH (normalmente `22`).
- `SSH_USER`: usuario de despliegue.
- `SSH_PRIVATE_KEY`: llave privada para acceso remoto.
- `DEPLOY_PATH`: ruta del proyecto en servidor.

## Variables recomendadas (Environment variables)
- `APP_NAME`
- `APP_ENV=production`
- `HEALTHCHECK_URL=http://127.0.0.1/health`

## Reglas de Seguridad
- No usar cuenta `root` para deploy.
- Llaves SSH con passphrase cuando sea posible.
- Rotación de llaves cada 90 días.
- Revocar acceso al retirar colaboradores.
- No imprimir secretos en logs de workflow.

## Validación de Acceso
Antes de habilitar deploy automático:
```bash
ssh -p <SSH_PORT> <SSH_USER>@<SSH_HOST>
cd <DEPLOY_PATH>
git status
```

## Gobernanza de Cambios
- Cada cambio de secretos debe registrarse en bitácora interna.
- Deploy en `production` debe requerir PR mergeado + aprobación de environment.
- Prohibido deploy directo desde ramas feature.

## Checklist de Configuración Inicial
- [ ] Environment `production` creado.
- [ ] Reviewers obligatorios configurados.
- [ ] Secretos cargados y verificados.
- [ ] Runner puede conectarse al host remoto.
- [ ] Healthcheck URL validada.
- [ ] Permisos mínimos aplicados.

