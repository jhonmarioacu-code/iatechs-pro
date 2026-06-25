#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise
# File: 09-supervisor.sh
#
# Configure Supervisor for Laravel Horizon
#
# Ubuntu 24.04
# AWS EC2
# Laravel 12
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise"
echo " Step 09 - Supervisor"
echo "========================================================="
echo ""

################################################################################
# ROOT
################################################################################

if [[ $EUID -ne 0 ]]; then
    echo "Execute as root"
    exit 1
fi

################################################################################
# CONFIG
################################################################################

APP_NAME="iatechs-pro"

APP_PATH="/var/www/${APP_NAME}"

APP_USER="www-data"

################################################################################
# VERIFY
################################################################################

if [[ ! -f "${APP_PATH}/artisan" ]]; then

    echo ""
    echo "ERROR:"
    echo "Laravel project not found."
    echo ""

    exit 1

fi

################################################################################
# LOG DIRECTORY
################################################################################

mkdir -p "${APP_PATH}/storage/logs"

chown -R ${APP_USER}:${APP_USER} \
    "${APP_PATH}/storage"

################################################################################
# HORIZON CONFIG
################################################################################

echo ""
echo "Creating Horizon Supervisor configuration..."
echo ""

cat > /etc/supervisor/conf.d/${APP_NAME}-horizon.conf <<EOF
[program:${APP_NAME}-horizon]

process_name=%(program_name)s

command=php ${APP_PATH}/artisan horizon

directory=${APP_PATH}

user=${APP_USER}

autostart=true

autorestart=true

startsecs=5

stopwaitsecs=3600

stopsignal=QUIT

stdout_logfile=${APP_PATH}/storage/logs/horizon.log

stderr_logfile=${APP_PATH}/storage/logs/horizon-error.log

redirect_stderr=true

numprocs=1
EOF

################################################################################
# RELOAD SUPERVISOR
################################################################################

echo ""
echo "Reloading Supervisor..."
echo ""

supervisorctl reread

supervisorctl update

################################################################################
# START HORIZON
################################################################################

echo ""
echo "Starting Horizon..."
echo ""

supervisorctl restart ${APP_NAME}-horizon || true

sleep 3

################################################################################
# STATUS
################################################################################

echo ""
echo "Supervisor Status"
echo ""
supervisorctl status

################################################################################
# HORIZON STATUS
################################################################################

echo ""
echo "Laravel Horizon Status"
echo ""

cd ${APP_PATH}

php artisan horizon:status || true

################################################################################
# ENABLE SERVICE
################################################################################

systemctl enable supervisor

systemctl restart supervisor

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 09 Completed Successfully"
echo "========================================================="
echo ""

echo "Installed:"
echo ""

echo "✓ Supervisor"
echo "✓ Horizon Auto Start"
echo "✓ Auto Restart"
echo "✓ Production Ready"

echo ""
echo "Logs:"
echo ""

echo "${APP_PATH}/storage/logs/horizon.log"
echo "${APP_PATH}/storage/logs/horizon-error.log"

echo ""
echo "Commands:"
echo ""

echo "supervisorctl status"
echo "supervisorctl restart ${APP_NAME}-horizon"
echo "php artisan horizon:status"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/10-deploy.sh"

echo ""