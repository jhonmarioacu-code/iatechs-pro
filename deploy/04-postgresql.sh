#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 04-postgresql.sh
# Purpose:
#   PostgreSQL 16 Installation
#   Database Creation
#   Laravel Configuration
#   Performance Tuning
#
# AWS EC2 Ubuntu 24.04
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 04 - PostgreSQL"
echo "========================================================="
echo ""

################################################################################
# ROOT VALIDATION
################################################################################

if [[ $EUID -ne 0 ]]; then
    echo "ERROR: Execute as root."
    echo ""
    echo "sudo bash deploy/04-postgresql.sh"
    exit 1
fi

################################################################################
# CONFIGURATION
################################################################################

DB_NAME="iatechs_pro"
DB_USER="iatechs_user"

echo ""
echo "========================================================="
echo "Database Configuration"
echo "========================================================="
echo ""

read -s -p "Database Password: " DB_PASSWORD
echo ""

################################################################################
# INSTALL POSTGRESQL
################################################################################

echo ""
echo "========================================================="
echo "Installing PostgreSQL..."
echo "========================================================="
echo ""

apt-get update -y

apt-get install -y \
postgresql \
postgresql-contrib

################################################################################
# START SERVICE
################################################################################

systemctl enable postgresql
systemctl start postgresql

################################################################################
# CREATE USER
################################################################################

echo ""
echo "========================================================="
echo "Creating Database User..."
echo "========================================================="
echo ""

sudo -u postgres psql <<EOF

DO \$\$
BEGIN

IF NOT EXISTS (
    SELECT
    FROM pg_catalog.pg_roles
    WHERE rolname='${DB_USER}'
)

THEN

CREATE ROLE ${DB_USER}
LOGIN
PASSWORD '${DB_PASSWORD}';

END IF;

END
\$\$;

EOF

################################################################################
# CREATE DATABASE
################################################################################

echo ""
echo "========================================================="
echo "Creating Database..."
echo "========================================================="
echo ""

sudo -u postgres psql <<EOF

SELECT
'CREATE DATABASE ${DB_NAME} OWNER ${DB_USER}'
WHERE NOT EXISTS (
    SELECT
    FROM pg_database
    WHERE datname='${DB_NAME}'
)\gexec

EOF

################################################################################
# GRANTS
################################################################################

echo ""
echo "========================================================="
echo "Granting Permissions..."
echo "========================================================="
echo ""

sudo -u postgres psql <<EOF

GRANT ALL PRIVILEGES
ON DATABASE ${DB_NAME}
TO ${DB_USER};

EOF

################################################################################
# SCHEMA PERMISSIONS
################################################################################

sudo -u postgres psql -d ${DB_NAME} <<EOF

ALTER SCHEMA public
OWNER TO ${DB_USER};

GRANT ALL
ON SCHEMA public
TO ${DB_USER};

EOF

################################################################################
# PERFORMANCE TUNING
################################################################################

echo ""
echo "========================================================="
echo "Applying Production Optimizations..."
echo "========================================================="
echo ""

PG_VERSION=$(ls /etc/postgresql)

CONF="/etc/postgresql/${PG_VERSION}/main/postgresql.conf"

cp $CONF ${CONF}.backup

sed -i "s/#listen_addresses =.*/listen_addresses = 'localhost'/" $CONF

################################################################################
# MEMORY
################################################################################

cat >> $CONF <<EOF

################################################################################
# IAtechsPro Optimization
################################################################################

shared_buffers = 2GB
effective_cache_size = 6GB

maintenance_work_mem = 512MB

work_mem = 16MB

wal_buffers = 16MB

min_wal_size = 1GB
max_wal_size = 4GB

random_page_cost = 1.1

effective_io_concurrency = 200

max_connections = 200

checkpoint_completion_target = 0.9

default_statistics_target = 100

################################################################################
EOF

################################################################################
# RESTART
################################################################################

systemctl restart postgresql

################################################################################
# TEST CONNECTION
################################################################################

echo ""
echo "========================================================="
echo "Testing Database Connection"
echo "========================================================="
echo ""

sudo -u postgres psql -c "\l"

################################################################################
# OUTPUT
################################################################################

echo ""
echo "========================================================="
echo "Laravel Configuration"
echo "========================================================="
echo ""

echo "DB_CONNECTION=pgsql"
echo "DB_HOST=127.0.0.1"
echo "DB_PORT=5432"
echo "DB_DATABASE=${DB_NAME}"
echo "DB_USERNAME=${DB_USER}"
echo "DB_PASSWORD=${DB_PASSWORD}"

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 04 Completed Successfully"
echo "========================================================="
echo ""

echo "Installed:"
echo ""

echo "✓ PostgreSQL"
echo "✓ Database Created"
echo "✓ User Created"
echo "✓ Production Tuning"
echo "✓ Laravel Ready"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/05-redis.sh"
echo ""