#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 06-nginx.sh
# Purpose:
#   Nginx Production Configuration
#   Laravel 12
#   PHP-FPM 8.4
#   AWS EC2
#
# Ubuntu 24.04
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 06 - Nginx"
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

PHP_VERSION="8.4"

################################################################################
# INSTALL NGINX
################################################################################

echo ""
echo "Installing Nginx..."
echo ""

apt-get update -y

apt-get install -y nginx

################################################################################
# ENABLE
################################################################################

systemctl enable nginx
systemctl start nginx

################################################################################
# REMOVE DEFAULT SITE
################################################################################

rm -f /etc/nginx/sites-enabled/default

################################################################################
# MAIN CONFIG
################################################################################

cat > /etc/nginx/sites-available/${APP_NAME} <<EOF

server {

    listen 80 default_server;

    server_name _;

    root ${APP_PATH}/public;

    index index.php index.html;

    charset utf-8;

    client_max_body_size 100M;

    add_header X-Frame-Options SAMEORIGIN;
    add_header X-Content-Type-Options nosniff;
    add_header X-XSS-Protection "1; mode=block";

    access_log /var/log/nginx/${APP_NAME}_access.log;
    error_log  /var/log/nginx/${APP_NAME}_error.log;

    location / {

        try_files \$uri \$uri/ /index.php?\$query_string;

    }

    location = /favicon.ico {

        access_log off;
        log_not_found off;

    }

    location = /robots.txt {

        access_log off;
        log_not_found off;

    }

    error_page 404 /index.php;

    location ~ \.php$ {

        include snippets/fastcgi-php.conf;

        fastcgi_pass unix:/run/php/php${PHP_VERSION}-fpm.sock;

        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;

        include fastcgi_params;

    }

    location ~ /\.(?!well-known).* {

        deny all;

    }
}

EOF

################################################################################
# ENABLE SITE
################################################################################

ln -sf \
/etc/nginx/sites-available/${APP_NAME} \
/etc/nginx/sites-enabled/${APP_NAME}

################################################################################
# NGINX GLOBAL OPTIMIZATION
################################################################################

NGINX_CONF="/etc/nginx/nginx.conf"

cp $NGINX_CONF ${NGINX_CONF}.backup

cat > $NGINX_CONF <<EOF

user www-data;

worker_processes auto;

pid /run/nginx.pid;

events {

    worker_connections 4096;

    multi_accept on;
}

http {

    sendfile on;

    tcp_nopush on;

    tcp_nodelay on;

    keepalive_timeout 65;

    types_hash_max_size 2048;

    server_tokens off;

    include /etc/nginx/mime.types;

    default_type application/octet-stream;

    access_log /var/log/nginx/access.log;

    error_log /var/log/nginx/error.log;

    gzip on;

    gzip_comp_level 5;

    gzip_min_length 256;

    gzip_proxied any;

    gzip_types
        text/plain
        text/css
        application/json
        application/javascript
        text/xml
        application/xml
        application/xml+rss
        text/javascript;

    include /etc/nginx/conf.d/*.conf;

    include /etc/nginx/sites-enabled/*;
}

EOF

################################################################################
# TEST
################################################################################

echo ""
echo "Testing Nginx..."
echo ""

nginx -t

################################################################################
# RESTART
################################################################################

systemctl restart nginx

################################################################################
# STATUS
################################################################################

systemctl status nginx --no-pager

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 06 Completed Successfully"
echo "========================================================="
echo ""

echo "Nginx configured for:"

echo ""

echo "Laravel 12"
echo "PHP ${PHP_VERSION}"
echo "AWS EC2"
echo "Production"

echo ""
echo "Project Path:"
echo ""

echo "${APP_PATH}"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/07-laravel.sh"

echo ""