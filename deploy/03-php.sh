#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 03-php.sh
# Purpose:
#   - PHP 8.4
#   - PHP-FPM
#   - Composer
#   - NodeJS 22
#   - NPM
#   - Laravel Requirements
#
# AWS EC2 Ubuntu 24.04
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 03 - PHP / Composer / NodeJS"
echo "========================================================="
echo ""

################################################################################
# ROOT VALIDATION
################################################################################

if [[ $EUID -ne 0 ]]; then
    echo "ERROR: Execute as root."
    echo ""
    echo "Example:"
    echo "sudo bash deploy/03-php.sh"
    exit 1
fi

################################################################################
# VARIABLES
################################################################################

PHP_VERSION="8.4"
NODE_VERSION="22"

################################################################################
# PHP REPOSITORY
################################################################################

echo ""
echo "========================================================="
echo "Installing PHP Repository..."
echo "========================================================="
echo ""

add-apt-repository ppa:ondrej/php -y

apt-get update -y

################################################################################
# INSTALL PHP
################################################################################

echo ""
echo "========================================================="
echo "Installing PHP ${PHP_VERSION}"
echo "========================================================="
echo ""

DEBIAN_FRONTEND=noninteractive apt-get install -y \

php${PHP_VERSION} \
php${PHP_VERSION}-fpm \
php${PHP_VERSION}-cli \
php${PHP_VERSION}-common \
php${PHP_VERSION}-pgsql \
php${PHP_VERSION}-curl \
php${PHP_VERSION}-xml \
php${PHP_VERSION}-mbstring \
php${PHP_VERSION}-zip \
php${PHP_VERSION}-bcmath \
php${PHP_VERSION}-intl \
php${PHP_VERSION}-gd \
php${PHP_VERSION}-soap \
php${PHP_VERSION}-redis \
php${PHP_VERSION}-opcache \
php${PHP_VERSION}-readline

################################################################################
# ENABLE PHP FPM
################################################################################

systemctl enable php${PHP_VERSION}-fpm
systemctl start php${PHP_VERSION}-fpm

################################################################################
# PHP.INI OPTIMIZATION
################################################################################

echo ""
echo "========================================================="
echo "Optimizing PHP..."
echo "========================================================="
echo ""

PHPINI="/etc/php/${PHP_VERSION}/fpm/php.ini"

sed -i "s/memory_limit = .*/memory_limit = 512M/" $PHPINI
sed -i "s/upload_max_filesize = .*/upload_max_filesize = 50M/" $PHPINI
sed -i "s/post_max_size = .*/post_max_size = 50M/" $PHPINI
sed -i "s/max_execution_time = .*/max_execution_time = 300/" $PHPINI

################################################################################
# OPCACHE
################################################################################

cat > /etc/php/${PHP_VERSION}/fpm/conf.d/99-opcache.ini <<EOF
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.revalidate_freq=0
opcache.validate_timestamps=0
EOF

systemctl restart php${PHP_VERSION}-fpm

################################################################################
# INSTALL COMPOSER
################################################################################

echo ""
echo "========================================================="
echo "Installing Composer..."
echo "========================================================="
echo ""

if ! command -v composer >/dev/null 2>&1
then

    curl -sS https://getcomposer.org/installer \
    -o /tmp/composer-setup.php

    php /tmp/composer-setup.php \
        --install-dir=/usr/local/bin \
        --filename=composer

    rm -f /tmp/composer-setup.php

fi

################################################################################
# INSTALL NODEJS 22
################################################################################

echo ""
echo "========================================================="
echo "Installing NodeJS ${NODE_VERSION}"
echo "========================================================="
echo ""

curl -fsSL https://deb.nodesource.com/setup_${NODE_VERSION}.x | bash -

apt-get install -y nodejs

################################################################################
# VERIFY INSTALLATIONS
################################################################################

echo ""
echo "========================================================="
echo "Versions Installed"
echo "========================================================="
echo ""

echo ""
echo "PHP:"
php -v

echo ""
echo "Composer:"
composer --version

echo ""
echo "Node:"
node -v

echo ""
echo "NPM:"
npm -v

################################################################################
# CLEAN
################################################################################

apt-get autoremove -y
apt-get autoclean -y

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 03 Completed Successfully"
echo "========================================================="
echo ""

echo "Installed:"
echo ""

echo "✓ PHP ${PHP_VERSION}"
echo "✓ PHP-FPM"
echo "✓ PostgreSQL Driver"
echo "✓ Redis Driver"
echo "✓ OPcache"
echo "✓ Composer"
echo "✓ NodeJS ${NODE_VERSION}"
echo "✓ NPM"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/04-postgresql.sh"
echo ""