#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 08-horizon.sh
# Purpose:
#   Laravel Horizon
#   Redis Queues
#   Production Configuration
#
# AWS EC2
# Ubuntu 24.04
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 08 - Horizon"
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

    echo "Laravel project not found."
    exit 1

fi

cd ${APP_PATH}

################################################################################
# VERIFY HORIZON
################################################################################

echo ""
echo "Checking Horizon..."
echo ""

if ! composer show laravel/horizon >/dev/null 2>&1; then

    echo ""
    echo "Installing Horizon..."
    echo ""

    composer require laravel/horizon

fi

################################################################################
# PUBLISH
################################################################################

if [[ ! -f "${APP_PATH}/config/horizon.php" ]]; then

    echo ""
    echo "Publishing Horizon..."
    echo ""

    php artisan horizon:install

fi

################################################################################
# HORIZON CONFIG
################################################################################

echo ""
echo "Configuring Horizon..."
echo ""

cat > "${APP_PATH}/config/horizon.php" <<'EOF'
<?php

use Laravel\Horizon\Horizon;

return [

    'domain' => null,

    'path' => 'horizon',

    'use' => 'default',

    'prefix' => env(
        'HORIZON_PREFIX',
        'iatechs_horizon:'
    ),

    'middleware' => ['web'],

    'waits' => [
        'redis:default' => 60,
    ],

    'trim' => [
        'recent' => 60,
        'pending' => 60,
        'completed' => 60,
        'recent_failed' => 10080,
        'failed' => 10080,
        'monitored' => 10080,
    ],

    'fast_termination' => false,

    'memory_limit' => 256,

    'defaults' => [

        'supervisor-1' => [

            'connection' => 'redis',

            'queue' => [
                'default'
            ],

            'balance' => 'auto',

            'autoScalingStrategy' => 'time',

            'maxProcesses' => 10,

            'maxTime' => 0,

            'maxJobs' => 0,

            'memory' => 256,

            'tries' => 3,

            'timeout' => 120,

            'nice' => 0,
        ],
    ],

    'environments' => [

        'production' => [

            'supervisor-1' => [

                'maxProcesses' => 20,

                'balanceMaxShift' => 1,

                'balanceCooldown' => 3,
            ],
        ],

        'local' => [

            'supervisor-1' => [

                'maxProcesses' => 3,
            ],
        ],
    ],
];
EOF

################################################################################
# ENVIRONMENT
################################################################################

echo ""
echo "Checking .env..."
echo ""

grep -q "^QUEUE_CONNECTION=redis" .env \
|| echo "QUEUE_CONNECTION=redis" >> .env

grep -q "^CACHE_STORE=redis" .env \
|| echo "CACHE_STORE=redis" >> .env

grep -q "^SESSION_DRIVER=redis" .env \
|| echo "SESSION_DRIVER=redis" >> .env

################################################################################
# CACHE
################################################################################

echo ""
echo "Refreshing Config..."
echo ""

php artisan optimize:clear

php artisan config:cache

################################################################################
# TEST HORIZON
################################################################################

echo ""
echo "Testing Horizon..."
echo ""

php artisan horizon:status || true

################################################################################
# PERMISSIONS
################################################################################

chown -R ${APP_USER}:${APP_USER} ${APP_PATH}

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 08 Completed Successfully"
echo "========================================================="
echo ""

echo "Installed:"
echo ""

echo "✓ Horizon"
echo "✓ Redis Queue"
echo "✓ Queue Monitoring"
echo "✓ Auto Scaling"
echo "✓ Production Configuration"

echo ""
echo "Horizon Dashboard:"
echo ""

echo "http://YOUR_SERVER_IP/horizon"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/09-supervisor.sh"

echo ""