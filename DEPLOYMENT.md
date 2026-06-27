# IAtechs Pro Deployment

Guia corta para preparar el codigo limpio en servidor segun `docs/operations` y `docs/standards`.

## Requisitos

- PHP 8.4
- Composer 2.x
- Node.js 22 LTS y npm
- PostgreSQL 17+
- Redis 7+
- Nginx
- Supervisor

## Instalacion automatica en AWS EC2

Servidor recomendado para esta guia:

- Ubuntu Server 24.04 LTS
- EC2 m7i-flex con 2 vCPU y 8 GB RAM
- 100 GB gp3
- Security Group con puertos `22`, `80` y `443`

Subir el proyecto por FileZilla/SFTP, por ejemplo a:

```text
/home/ubuntu/iatechs-pro
```

Entrar por SSH:

```bash
ssh -i tu-llave.pem ubuntu@IP_PUBLICA
cd /home/ubuntu/iatechs-pro
sudo bash deploy/aws-ec2-production-setup.sh
```

### Instalacion recomendada (archivo unico de configuracion)

El instalador `deploy/one-file-production.sh` ahora puede leer todo desde un solo archivo:

```bash
cd /home/ubuntu/iatechs-pro
cp deploy/install.conf.example deploy/install.conf
nano deploy/install.conf
sudo bash deploy/one-file-production.sh
```

Notas:

- No necesitas pasar variables largas por consola si usas `deploy/install.conf`.
- Si `RUN_SEEDERS=yes` y no defines `PRODUCTION_ADMIN_EMAIL`/`PRODUCTION_ADMIN_PASSWORD`, el seeder corre pero no crea super admin.
- Si `DOMAIN` es vacio (o es IP), el script no intenta emitir SSL automaticamente.
- Nginx detecta automaticamente el socket real de PHP-FPM para evitar errores de version.

Tambien se puede ejecutar con dominio y correo desde el inicio:

```bash
DOMAIN=app.tudominio.com ADMIN_EMAIL=admin@tudominio.com \
sudo -E bash deploy/aws-ec2-production-setup.sh
```

Variables utiles para instalacion repetible:

```bash
DOMAIN=app.tudominio.com \
ADMIN_EMAIL=admin@tudominio.com \
DB_NAME=iatechs_pro \
DB_USER=iatechs_pro \
DB_PASSWORD='cambia-esta-clave' \
sudo -E bash deploy/aws-ec2-production-setup.sh
```

El script instala y configura:

- UFW y Fail2ban
- PHP 8.4 y PHP-FPM
- Composer
- Node.js 22
- PostgreSQL 17
- Redis
- Nginx
- Supervisor
- Laravel Horizon
- Cron de Laravel Scheduler
- Build de Vite
- `.env` de produccion
- Health check `/health`

Reportes creados en el servidor:

```text
/root/iatechs-pro-install.log
/root/iatechs-pro-install-report.txt
/root/iatechs-pro-install-secrets.txt
```

## Instalacion manual

Ruta recomendada para produccion manual:

```text
/var/www/iatechs-pro
```

Evitar servir directamente desde `/home/ubuntu/iatechs-pro` salvo que `/home/ubuntu` permita acceso de travesia a `www-data`. Si Nginx apunta a una app dentro de `/home/ubuntu` y devuelve `404` aunque `php artisan serve` responda bien, corregir con una de estas opciones:

```bash
sudo chmod 755 /home/ubuntu
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
sudo systemctl reload nginx
sudo systemctl restart php8.4-fpm
```

O mover la app a `/var/www/iatechs-pro` y apuntar Nginx a:

```nginx
root /var/www/iatechs-pro/public;
```

```bash
composer install --no-dev --optimize-autoloader
npm ci
npm run build
cp .env.example .env
php artisan key:generate
```

Configurar `.env` con valores reales de produccion:

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=iatechs_pro
DB_USERNAME=iatechs_pro
DB_PASSWORD=clave-real-de-postgres
QUEUE_CONNECTION=redis
CACHE_STORE=redis
SESSION_DRIVER=redis
FILESYSTEM_DISK=s3
```

Si PostgreSQL ya existe, crear y preparar la base antes de migrar:

```bash
sudo -u postgres psql
```

```sql
CREATE ROLE iatechs_pro LOGIN PASSWORD 'clave-real-de-postgres';
CREATE DATABASE iatechs_pro OWNER iatechs_pro;
\c iatechs_pro
ALTER SCHEMA public OWNER TO iatechs_pro;
GRANT USAGE, CREATE ON SCHEMA public TO iatechs_pro;
GRANT ALL PRIVILEGES ON DATABASE iatechs_pro TO iatechs_pro;
\q
```


## Migracion del servidor de prueba a ruta profesional

Cuando una prueba quedo funcionando en `/home/ubuntu/iatechs-pro`, moverla a la ruta profesional antes de considerarla produccion controlada:

```bash
sudo mkdir -p /var/www
sudo rsync -a --delete /home/ubuntu/iatechs-pro/ /var/www/iatechs-pro/
sudo chown -R www-data:www-data /var/www/iatechs-pro
sudo find /var/www/iatechs-pro -type d -exec chmod 775 {} \;
sudo find /var/www/iatechs-pro -type f -exec chmod 664 {} \;
sudo chmod +x /var/www/iatechs-pro/artisan
sudo chmod 660 /var/www/iatechs-pro/.env
```

Actualizar Nginx:

```nginx
root /var/www/iatechs-pro/public;
```

Actualizar Supervisor y cron para que usen `/var/www/iatechs-pro`, luego recargar servicios:

```bash
sudo nginx -t
sudo systemctl reload nginx
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl restart iatechs-pro-horizon
sudo systemctl restart cron
cd /var/www/iatechs-pro && sudo -u www-data php artisan optimize:clear
cd /var/www/iatechs-pro && sudo -u www-data php artisan config:cache
curl -i http://127.0.0.1/health
```
## Activacion

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan storage:link
php artisan horizon:terminate
```

Si una migracion falla durante una instalacion nueva, corregir/subir el cambio, limpiar cache y repetir:

```bash
php artisan optimize:clear
php artisan migrate --force
```

## Validacion

```bash
php artisan test
composer audit
curl http://127.0.0.1/health
curl https://tu-dominio.com/health
```

El endpoint `/health` debe responder `status: ok`.

## Observabilidad externa (opcional)

Para levantar Prometheus + Alertmanager + Grafana con reglas SLO/SLA operativas:

```bash
cp docker/observability/secrets/obs_exporter_token.example docker/observability/secrets/obs_exporter_token
cp docker/observability/secrets/slack_webhook_url.example docker/observability/secrets/slack_webhook_url
docker compose --profile observability up -d prometheus alertmanager grafana
```

Smoke postdeploy automatizable:

```bash
bash deploy/observability-postdeploy-check.sh
```

Validar:

```text
Prometheus target iatechs_app en UP
Grafana dashboard IAtechs Observability cargado
Alertmanager enviando notificaciones
```
