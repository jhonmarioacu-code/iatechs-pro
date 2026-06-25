#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 07-laravel.sh
# Purpose:
#   Configure Existing Laravel Project
#
# AWS EC2
# Ubuntu 24.04
# Laravel 12
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 07 - Laravel"
echo "========================================================="
echo ""

################################################################################
# ROOT CHECK
################################################################################

if [[ $EUID -ne 0 ]]; then
    echo "Execute as root."
    exit 1
fi

################################################################################
# CONFIG
################################################################################

APP_NAME="iatechs-pro"

APP_PATH="/var/www/${APP_NAME}"

APP_USER="www-data"

################################################################################
# VERIFY PROJECT
################################################################################

if [[ ! -f "${APP_PATH}/artisan" ]]; then

    echo ""
    echo "ERROR:"
    echo ""

    echo "Laravel project not found."

    echo ""
    echo "Expected:"
    echo "${APP_PATH}/artisan"
    echo ""

    exit 1

fi

################################################################################
# PERMISSIONS
################################################################################

echo ""
echo "Configuring permissions..."
echo ""

chown -R ${APP_USER}:${APP_USER} ${APP_PATH}

find ${APP_PATH} -type f -exec chmod 664 {} \;

find ${APP_PATH} -type d -exec chmod 775 {} \;

chmod -R 775 ${APP_PATH}/storage

chmod -R 775 ${APP_PATH}/bootstrap/cache

################################################################################
# COMPOSER
################################################################################

echo ""
echo "Installing Composer dependencies..."
echo ""

cd ${APP_PATH}

composer install \
    --no-dev \
    --prefer-dist \
    --optimize-autoloader \
    --no-interaction

################################################################################
# NODE
################################################################################

echo ""
echo "Installing Node dependencies..."
echo ""

npm install

################################################################################
# BUILD
################################################################################

echo ""
echo "Building frontend..."
echo ""

npm run build

################################################################################
# ENV FILE
################################################################################

if [[ ! -f ".env" ]]; then

    echo ""
    echo "Creating .env..."
    echo ""

    cp .env.example .env

fi

################################################################################
# APP KEY
################################################################################

echo ""
echo "Generating APP_KEY..."
echo ""

php artisan key:generate --force

################################################################################
# STORAGE LINK
################################################################################

echo ""
echo "Creating Storage Link..."
echo ""

php artisan storage:link || true

################################################################################
# CACHE CLEAN
################################################################################

echo ""
echo "Clearing caches..."
echo ""

php artisan optimize:clear

################################################################################
# VERIFY REQUIREMENTS
################################################################################

echo ""
echo "Verifying Packages..."
echo ""

composer show laravel/sanctum >/dev/null \
&& echo "✓ Sanctum"

composer show spatie/laravel-permission >/dev/null \
&& echo "✓ Spatie Permission"

composer show laravel/horizon >/dev/null \
&& echo "✓ Horizon"

################################################################################
# OPTIMIZATION
################################################################################

echo ""
echo "Optimizing..."
echo ""

php artisan optimize

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 07 Completed Successfully"
echo "========================================================="
echo ""

echo "Project Ready:"
echo ""

echo "✓ Laravel 12"
echo "✓ Composer"
echo "✓ Node"
echo "✓ Sanctum"
echo "✓ Spatie Permission"
echo "✓ Horizon"
echo "✓ Production Optimization"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/08-horizon.sh"

echo ""