#!/usr/bin/env bash

set -Eeuo pipefail
IFS=$'\n\t'

################################################################################
# IAtechs Pro - One File Production Installer
#
# Target:
#   AWS EC2 Ubuntu Server 24.04 LTS
#   Instance: m7i-flex, 2 vCPU, 8 GB RAM, 100 GB gp3
#   Open ports: 22, 80, 443
#
# Usage after uploading the project with FileZilla/SFTP:
#   cd /path/to/uploaded/iatechs-pro
#   sudo bash deploy/one-file-production.sh
#
# Optional non-interactive variables:
#   DOMAIN=app.example.com ADMIN_EMAIL=admin@example.com DB_PASSWORD='secret' \
#   PRODUCTION_ADMIN_EMAIL=admin@example.com PRODUCTION_ADMIN_PASSWORD='StrongPass!' \
#   sudo -E bash deploy/one-file-production.sh
################################################################################

APP_NAME="${APP_NAME:-iatechs-pro}"
APP_TITLE="${APP_TITLE:-IAtechs Pro}"
APP_PATH="${APP_PATH:-/var/www/${APP_NAME}}"
APP_USER="${APP_USER:-www-data}"
PHP_VERSION="${PHP_VERSION:-8.4}"
NODE_MAJOR="${NODE_MAJOR:-22}"
POSTGRES_VERSION="${POSTGRES_VERSION:-17}"
TIMEZONE="${TIMEZONE:-UTC}"
DB_NAME="${DB_NAME:-iatechs_pro}"
DB_USER="${DB_USER:-iatechs_pro}"
REDIS_MAXMEMORY="${REDIS_MAXMEMORY:-512mb}"
FILESYSTEM_DISK="${FILESYSTEM_DISK:-local}"
RUN_MIGRATIONS="${RUN_MIGRATIONS:-yes}"
RUN_SEEDERS="${RUN_SEEDERS:-yes}"
SEEDER_CLASS="${SEEDER_CLASS:-ProductionSeeder}"
ENABLE_SSL="${ENABLE_SSL:-ask}"
HARDEN_SSH="${HARDEN_SSH:-yes}"
REMOVE_NODE_MODULES="${REMOVE_NODE_MODULES:-yes}"

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
REPO_ROOT="$(cd "${SCRIPT_DIR}/.." && pwd)"
SOURCE_PATH="${SOURCE_PATH:-${REPO_ROOT}}"
LOG_FILE="/root/${APP_NAME}-install.log"
SECRETS_FILE="/root/${APP_NAME}-install-secrets.txt"

DOMAIN="${DOMAIN:-}"
ADMIN_EMAIL="${ADMIN_EMAIL:-}"
DB_PASSWORD="${DB_PASSWORD:-}"
PRODUCTION_ADMIN_EMAIL="${PRODUCTION_ADMIN_EMAIL:-}"
PRODUCTION_ADMIN_PASSWORD="${PRODUCTION_ADMIN_PASSWORD:-}"
PRODUCTION_ADMIN_NAME="${PRODUCTION_ADMIN_NAME:-IAtechs Super Admin}"
INSTALL_CONFIG_FILE="${INSTALL_CONFIG_FILE:-${SOURCE_PATH}/deploy/install.conf}"

export DEBIAN_FRONTEND=noninteractive

log() {
    printf '\n[%s] %s\n' "$(date '+%Y-%m-%d %H:%M:%S')" "$*" | tee -a "${LOG_FILE}"
}

fail() {
    printf '\nERROR: %s\n' "$*" | tee -a "${LOG_FILE}" >&2
    exit 1
}

on_error() {
    local line="${1:-unknown}"
    fail "Installation failed near line ${line}. Check ${LOG_FILE}."
}

trap 'on_error $LINENO' ERR

load_install_config_if_present() {
    if [[ -f "${INSTALL_CONFIG_FILE}" ]]; then
        log "Loading install configuration from ${INSTALL_CONFIG_FILE}"
        set -a
        # shellcheck disable=SC1090
        source "${INSTALL_CONFIG_FILE}"
        set +a
    fi
}

require_root() {
    if [[ "${EUID}" -ne 0 ]]; then
        fail "Run this script as root: sudo bash deploy/one-file-production.sh"
    fi
}

prompt_if_empty() {
    local var_name="$1"
    local message="$2"
    local default="${3:-}"
    local secret="${4:-no}"
    local value="${!var_name:-}"

    if [[ -n "${value}" ]]; then
        return
    fi

    if [[ ! -t 0 ]]; then
        printf -v "${var_name}" '%s' "${default}"
        return
    fi

    if [[ "${secret}" == "yes" ]]; then
        read -r -s -p "${message}" value
        echo ""
    else
        read -r -p "${message}" value
    fi

    if [[ -z "${value}" ]]; then
        value="${default}"
    fi

    printf -v "${var_name}" '%s' "${value}"
}

validate_identifier() {
    local value="$1"
    local label="$2"

    if [[ ! "${value}" =~ ^[a-zA-Z_][a-zA-Z0-9_]*$ ]]; then
        fail "${label} must contain only letters, numbers and underscores, and cannot start with a number."
    fi
}

detect_public_ip() {
    local token public_ip

    token="$(curl -fsS --max-time 2 -X PUT \
        -H 'X-aws-ec2-metadata-token-ttl-seconds: 60' \
        http://169.254.169.254/latest/api/token 2>/dev/null || true)"

    if [[ -n "${token}" ]]; then
        public_ip="$(curl -fsS --max-time 2 \
            -H "X-aws-ec2-metadata-token: ${token}" \
            http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null || true)"
    else
        public_ip="$(curl -fsS --max-time 2 http://169.254.169.254/latest/meta-data/public-ipv4 2>/dev/null || true)"
    fi

    if [[ -z "${public_ip:-}" ]]; then
        public_ip="$(hostname -I | awk '{print $1}')"
    fi

    printf '%s' "${public_ip:-127.0.0.1}"
}

env_value() {
    local value="$1"

    if [[ "${value}" =~ [[:space:]#\"\'\\] ]]; then
        value="${value//\\/\\\\}"
        value="${value//\"/\\\"}"
        printf '"%s"' "${value}"
    else
        printf '%s' "${value}"
    fi
}

set_env() {
    local key="$1"
    local raw_value="$2"
    local file="${APP_PATH}/.env"
    local rendered escaped

    rendered="$(env_value "${raw_value}")"
    escaped="$(printf '%s' "${rendered}" | sed -e 's/[\/&]/\\&/g')"

    if grep -qE "^${key}=" "${file}"; then
        sed -i "s/^${key}=.*/${key}=${escaped}/" "${file}"
    else
        printf '%s=%s\n' "${key}" "${rendered}" >> "${file}"
    fi
}

run_app() {
    local command="$*"
    sudo -u "${APP_USER}" -H env COMPOSER_HOME="/var/www/.composer" HOME="/var/www" \
        bash -lc "cd '${APP_PATH}' && ${command}"
}

install_base_system() {
    log "Updating Ubuntu and installing base packages"

    apt-get update -y
    apt-get upgrade -y

    apt-get install -y \
        acl \
        apt-transport-https \
        bash-completion \
        ca-certificates \
        cron \
        curl \
        fail2ban \
        git \
        gnupg \
        htop \
        jq \
        lsb-release \
        logrotate \
        nano \
        net-tools \
        openssl \
        redis-tools \
        rsync \
        software-properties-common \
        supervisor \
        tree \
        ufw \
        unzip \
        vim \
        wget \
        zip

    timedatectl set-timezone "${TIMEZONE}"
    systemctl enable cron supervisor fail2ban
}

configure_firewall_and_ssh() {
    log "Configuring firewall, Fail2ban and SSH hardening"

    ufw --force reset
    ufw default deny incoming
    ufw default allow outgoing
    ufw allow 22/tcp comment "SSH"
    ufw allow 80/tcp comment "HTTP"
    ufw allow 443/tcp comment "HTTPS"
    ufw --force enable

    cat > /etc/fail2ban/jail.local <<'EOF'
[DEFAULT]
bantime = 1h
findtime = 10m
maxretry = 5
backend = systemd

[sshd]
enabled = true
EOF

    systemctl restart fail2ban

    if [[ "${HARDEN_SSH}" == "yes" ]]; then
        cat > /etc/ssh/sshd_config.d/99-iatechs-pro-hardening.conf <<'EOF'
PermitRootLogin no
PasswordAuthentication no
KbdInteractiveAuthentication no
X11Forwarding no
MaxAuthTries 5
ClientAliveInterval 300
ClientAliveCountMax 2
EOF

        sshd -t
        systemctl restart ssh || systemctl restart sshd
    fi
}

configure_swap() {
    if swapon --show | grep -q .; then
        log "Swap already configured"
        return
    fi

    log "Creating 2 GB swap file"

    fallocate -l 2G /swapfile || dd if=/dev/zero of=/swapfile bs=1M count=2048
    chmod 600 /swapfile
    mkswap /swapfile
    swapon /swapfile

    if ! grep -q '^/swapfile ' /etc/fstab; then
        echo '/swapfile none swap sw 0 0' >> /etc/fstab
    fi
}

install_php_composer_node() {
    log "Installing PHP ${PHP_VERSION}, Composer and Node.js ${NODE_MAJOR}"

    add-apt-repository ppa:ondrej/php -y
    apt-get update -y

    apt-get install -y \
        "php${PHP_VERSION}" \
        "php${PHP_VERSION}-bcmath" \
        "php${PHP_VERSION}-cli" \
        "php${PHP_VERSION}-common" \
        "php${PHP_VERSION}-curl" \
        "php${PHP_VERSION}-fpm" \
        "php${PHP_VERSION}-gd" \
        "php${PHP_VERSION}-intl" \
        "php${PHP_VERSION}-mbstring" \
        "php${PHP_VERSION}-opcache" \
        "php${PHP_VERSION}-pgsql" \
        "php${PHP_VERSION}-readline" \
        "php${PHP_VERSION}-redis" \
        "php${PHP_VERSION}-soap" \
        "php${PHP_VERSION}-xml" \
        "php${PHP_VERSION}-zip"

    update-alternatives --set php "/usr/bin/php${PHP_VERSION}" || true

    local fpm_ini="/etc/php/${PHP_VERSION}/fpm/php.ini"
    local cli_ini="/etc/php/${PHP_VERSION}/cli/php.ini"

    sed -i 's/^memory_limit = .*/memory_limit = 512M/' "${fpm_ini}"
    sed -i 's/^upload_max_filesize = .*/upload_max_filesize = 100M/' "${fpm_ini}"
    sed -i 's/^post_max_size = .*/post_max_size = 100M/' "${fpm_ini}"
    sed -i 's/^max_execution_time = .*/max_execution_time = 300/' "${fpm_ini}"
    sed -i 's/^memory_limit = .*/memory_limit = -1/' "${cli_ini}"

    cat > "/etc/php/${PHP_VERSION}/fpm/conf.d/99-iatechs-pro-opcache.ini" <<'EOF'
opcache.enable=1
opcache.enable_cli=1
opcache.memory_consumption=256
opcache.interned_strings_buffer=16
opcache.max_accelerated_files=20000
opcache.validate_timestamps=0
opcache.revalidate_freq=0
EOF

    local pool="/etc/php/${PHP_VERSION}/fpm/pool.d/www.conf"
    sed -i 's/^pm = .*/pm = dynamic/' "${pool}"
    sed -i 's/^pm.max_children = .*/pm.max_children = 20/' "${pool}"
    sed -i 's/^pm.start_servers = .*/pm.start_servers = 4/' "${pool}"
    sed -i 's/^pm.min_spare_servers = .*/pm.min_spare_servers = 2/' "${pool}"
    sed -i 's/^pm.max_spare_servers = .*/pm.max_spare_servers = 6/' "${pool}"

    systemctl enable "php${PHP_VERSION}-fpm"
    systemctl restart "php${PHP_VERSION}-fpm"

    if ! command -v composer >/dev/null 2>&1; then
        local expected actual
        expected="$(curl -fsSL https://composer.github.io/installer.sig)"
        curl -fsSL https://getcomposer.org/installer -o /tmp/composer-setup.php
        actual="$(php -r "echo hash_file('sha384', '/tmp/composer-setup.php');")"

        if [[ "${expected}" != "${actual}" ]]; then
            rm -f /tmp/composer-setup.php
            fail "Composer installer signature verification failed."
        fi

        php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer
        rm -f /tmp/composer-setup.php
    fi

    curl -fsSL "https://deb.nodesource.com/setup_${NODE_MAJOR}.x" | bash -
    apt-get install -y nodejs

    php -m | grep -q '^redis$' || fail "PHP redis extension is not enabled."
    php -m | grep -q '^pdo_pgsql$' || fail "PHP pdo_pgsql extension is not enabled."
    php -m | grep -q '^pcntl$' || log "Warning: pcntl extension not visible in php -m; Horizon may fail if CLI lacks pcntl."
    php -m | grep -q '^posix$' || log "Warning: posix extension not visible in php -m; Horizon may fail if CLI lacks posix."
}

install_postgresql() {
    log "Installing and configuring PostgreSQL ${POSTGRES_VERSION}"

    install -d /usr/share/postgresql-common/pgdg
    curl -fsSL -o /usr/share/postgresql-common/pgdg/apt.postgresql.org.asc \
        https://www.postgresql.org/media/keys/ACCC4CF8.asc

    local codename
    codename="$(. /etc/os-release && echo "${VERSION_CODENAME}")"
    echo "deb [signed-by=/usr/share/postgresql-common/pgdg/apt.postgresql.org.asc] https://apt.postgresql.org/pub/repos/apt ${codename}-pgdg main" \
        > /etc/apt/sources.list.d/pgdg.list

    apt-get update -y
    apt-get install -y \
        "postgresql-${POSTGRES_VERSION}" \
        "postgresql-client-${POSTGRES_VERSION}" \
        postgresql-contrib

    systemctl enable postgresql
    systemctl start postgresql

    local escaped_password
    escaped_password="${DB_PASSWORD//\'/\'\'}"

    sudo -u postgres psql -v ON_ERROR_STOP=1 <<SQL
DO \$\$
BEGIN
    IF NOT EXISTS (
        SELECT FROM pg_catalog.pg_roles WHERE rolname = '${DB_USER}'
    ) THEN
        CREATE ROLE "${DB_USER}" LOGIN PASSWORD '${escaped_password}';
    ELSE
        ALTER ROLE "${DB_USER}" WITH LOGIN PASSWORD '${escaped_password}';
    END IF;
END
\$\$;

SELECT 'CREATE DATABASE "${DB_NAME}" OWNER "${DB_USER}"'
WHERE NOT EXISTS (
    SELECT FROM pg_database WHERE datname = '${DB_NAME}'
)\gexec

ALTER DATABASE "${DB_NAME}" OWNER TO "${DB_USER}";
SQL

    sudo -u postgres psql -v ON_ERROR_STOP=1 -d "${DB_NAME}" <<SQL
ALTER SCHEMA public OWNER TO "${DB_USER}";
GRANT ALL PRIVILEGES ON DATABASE "${DB_NAME}" TO "${DB_USER}";
GRANT USAGE, CREATE ON SCHEMA public TO "${DB_USER}";
GRANT ALL PRIVILEGES ON ALL TABLES IN SCHEMA public TO "${DB_USER}";
GRANT ALL PRIVILEGES ON ALL SEQUENCES IN SCHEMA public TO "${DB_USER}";
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON TABLES TO "${DB_USER}";
ALTER DEFAULT PRIVILEGES IN SCHEMA public GRANT ALL PRIVILEGES ON SEQUENCES TO "${DB_USER}";
SQL

    PGPASSWORD="${DB_PASSWORD}" psql -h 127.0.0.1 -U "${DB_USER}" -d "${DB_NAME}" -v ON_ERROR_STOP=1 <<'SQL'
CREATE TABLE public.__iatechs_install_permission_check (id integer);
DROP TABLE public.__iatechs_install_permission_check;
SQL

    local pg_conf_dir="/etc/postgresql/${POSTGRES_VERSION}/main/conf.d"
    install -d "${pg_conf_dir}"

    cat > "${pg_conf_dir}/iatechs-pro.conf" <<'EOF'
listen_addresses = 'localhost'
shared_buffers = '2GB'
effective_cache_size = '6GB'
maintenance_work_mem = '512MB'
work_mem = '16MB'
wal_buffers = '16MB'
min_wal_size = '1GB'
max_wal_size = '4GB'
random_page_cost = 1.1
effective_io_concurrency = 200
max_connections = 100
checkpoint_completion_target = 0.9
default_statistics_target = 100
EOF

    systemctl restart postgresql
}

install_redis() {
    log "Installing and configuring Redis"

    apt-get install -y redis-server

    local redis_conf="/etc/redis/redis.conf"
    cp "${redis_conf}" "${redis_conf}.backup.$(date +%Y%m%d%H%M%S)"

    sed -i 's/^supervised .*/supervised systemd/' "${redis_conf}"
    sed -i 's/^bind .*/bind 127.0.0.1 ::1/' "${redis_conf}"
    sed -i 's/^protected-mode .*/protected-mode yes/' "${redis_conf}"
    sed -i '/^# IAtechs Pro Optimization$/,/^# End IAtechs Pro Optimization$/d' "${redis_conf}"

    cat >> "${redis_conf}" <<EOF

# IAtechs Pro Optimization
maxmemory ${REDIS_MAXMEMORY}
maxmemory-policy allkeys-lru
tcp-keepalive 60
timeout 0
appendonly yes
appendfsync everysec
# End IAtechs Pro Optimization
EOF

    systemctl enable redis-server
    systemctl restart redis-server
    redis-cli ping | grep -q PONG
}

install_nginx() {
    log "Installing and configuring Nginx"

    apt-get install -y nginx

    local public_ip server_name fpm_socket
    public_ip="$(detect_public_ip)"
    server_name="${public_ip}"

    if [[ -n "${DOMAIN}" ]]; then
        server_name="${DOMAIN}"
    fi

    fpm_socket="/run/php/php${PHP_VERSION}-fpm.sock"
    if [[ ! -S "${fpm_socket}" ]]; then
        fpm_socket="$(find /run/php -maxdepth 1 -type s -name 'php*-fpm.sock' | head -n 1 || true)"
    fi
    if [[ -z "${fpm_socket}" ]]; then
        fail "Could not detect php-fpm socket under /run/php."
    fi

    rm -f /etc/nginx/sites-enabled/default

    cat > "/etc/nginx/sites-available/${APP_NAME}" <<EOF
server {
    listen 80;
    listen [::]:80;
    server_name ${server_name};

    root ${APP_PATH}/public;
    index index.php index.html;
    charset utf-8;

    client_max_body_size 100M;
    server_tokens off;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    access_log /var/log/nginx/${APP_NAME}_access.log;
    error_log /var/log/nginx/${APP_NAME}_error.log;

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
        fastcgi_pass unix:${fpm_socket};
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
EOF

    ln -sf "/etc/nginx/sites-available/${APP_NAME}" "/etc/nginx/sites-enabled/${APP_NAME}"
    nginx -t
    systemctl enable nginx
    systemctl restart nginx
}

prepare_project_files() {
    log "Preparing Laravel project at ${APP_PATH}"

    if [[ ! -f "${SOURCE_PATH}/artisan" && ! -f "${APP_PATH}/artisan" ]]; then
        fail "Laravel project not found. Upload the project first, then run this script from the project root."
    fi

    if [[ -f "${SOURCE_PATH}/artisan" && "${SOURCE_PATH}" != "${APP_PATH}" ]]; then
        install -d -m 0755 "${APP_PATH}"
        rsync -a \
            --exclude='.git' \
            --exclude='.env' \
            --exclude='vendor' \
            --exclude='node_modules' \
            --exclude='public/build' \
            --exclude='storage/logs/*.log' \
            --exclude='storage/framework/cache/data/*' \
            --exclude='storage/framework/sessions/*' \
            --exclude='storage/framework/testing/*' \
            --exclude='storage/framework/views/*' \
            "${SOURCE_PATH}/" "${APP_PATH}/"
    fi

    install -d -m 0775 \
        "${APP_PATH}/bootstrap/cache" \
        "${APP_PATH}/storage/app/public" \
        "${APP_PATH}/storage/framework/cache/data" \
        "${APP_PATH}/storage/framework/sessions" \
        "${APP_PATH}/storage/framework/testing" \
        "${APP_PATH}/storage/framework/views" \
        "${APP_PATH}/storage/logs"

    install -d -o "${APP_USER}" -g "${APP_USER}" /var/www/.composer

    chown -R "${APP_USER}:${APP_USER}" "${APP_PATH}"
    find "${APP_PATH}" -type f -exec chmod 664 {} \;
    find "${APP_PATH}" -type d -exec chmod 775 {} \;
    chmod +x "${APP_PATH}/artisan"
}

configure_laravel() {
    log "Installing Laravel dependencies and configuring environment"

    cd "${APP_PATH}"

    run_app composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction

    if [[ -f package-lock.json ]]; then
        run_app npm ci
    else
        run_app npm install
    fi

    run_app npm run build

    if [[ "${REMOVE_NODE_MODULES}" == "yes" ]]; then
        rm -rf "${APP_PATH}/node_modules"
    fi

    if [[ ! -f "${APP_PATH}/.env" ]]; then
        cp "${APP_PATH}/.env.example" "${APP_PATH}/.env"
        chown "${APP_USER}:${APP_USER}" "${APP_PATH}/.env"
        chmod 660 "${APP_PATH}/.env"
    fi

    local public_ip app_url
    public_ip="$(detect_public_ip)"

    if [[ -n "${DOMAIN}" ]]; then
        app_url="https://${DOMAIN}"
    else
        app_url="http://${public_ip}"
    fi

    set_env APP_NAME "${APP_TITLE}"
    set_env APP_ENV production
    set_env APP_DEBUG false
    set_env APP_URL "${app_url}"
    set_env LOG_CHANNEL stack
    set_env LOG_LEVEL error
    set_env DB_CONNECTION pgsql
    set_env DB_HOST 127.0.0.1
    set_env DB_PORT 5432
    set_env DB_DATABASE "${DB_NAME}"
    set_env DB_USERNAME "${DB_USER}"
    set_env DB_PASSWORD "${DB_PASSWORD}"
    set_env REDIS_CLIENT phpredis
    set_env REDIS_HOST 127.0.0.1
    set_env REDIS_PASSWORD null
    set_env REDIS_PORT 6379
    set_env CACHE_STORE redis
    set_env QUEUE_CONNECTION redis
    set_env SESSION_DRIVER redis
    set_env SESSION_ENCRYPT true
    set_env FILESYSTEM_DISK "${FILESYSTEM_DISK}"
    set_env HORIZON_PREFIX iatechs
    set_env HORIZON_MAX_PROCESSES 4
    set_env PRODUCTION_ADMIN_EMAIL "${PRODUCTION_ADMIN_EMAIL}"
    set_env PRODUCTION_ADMIN_PASSWORD "${PRODUCTION_ADMIN_PASSWORD}"
    set_env PRODUCTION_ADMIN_NAME "${PRODUCTION_ADMIN_NAME}"

    if ! grep -qE '^APP_KEY=base64:.+' "${APP_PATH}/.env"; then
        run_app php artisan key:generate --force
    fi

    run_app php artisan storage:link || true
    run_app php artisan optimize:clear

    if [[ "${RUN_MIGRATIONS}" == "yes" ]]; then
        run_app php artisan migrate --force
    fi

    if [[ "${RUN_SEEDERS}" == "yes" ]]; then
        run_app php artisan db:seed --class="${SEEDER_CLASS}" --force
    fi

    run_app php artisan config:cache
    run_app php artisan route:cache
    run_app php artisan view:cache
    run_app php artisan event:cache

    chown -R "${APP_USER}:${APP_USER}" "${APP_PATH}"
    chmod -R 775 "${APP_PATH}/storage" "${APP_PATH}/bootstrap/cache"
}

configure_supervisor_and_scheduler() {
    log "Configuring Supervisor for Horizon and cron for Laravel Scheduler"

    cat > "/etc/supervisor/conf.d/${APP_NAME}-horizon.conf" <<EOF
[program:${APP_NAME}-horizon]
process_name=%(program_name)s
command=/usr/bin/php ${APP_PATH}/artisan horizon
directory=${APP_PATH}
user=${APP_USER}
autostart=true
autorestart=true
startsecs=5
stopwaitsecs=3600
stopsignal=QUIT
stdout_logfile=${APP_PATH}/storage/logs/horizon.log
stderr_logfile=${APP_PATH}/storage/logs/horizon-error.log
redirect_stderr=false
numprocs=1
EOF

    supervisorctl reread
    supervisorctl update
    supervisorctl restart "${APP_NAME}-horizon" || true
    systemctl enable supervisor
    systemctl restart supervisor

    cat > "/etc/cron.d/${APP_NAME}" <<EOF
* * * * * ${APP_USER} cd ${APP_PATH} && /usr/bin/php artisan schedule:run >> /dev/null 2>&1
EOF

    chmod 0644 "/etc/cron.d/${APP_NAME}"
    systemctl restart cron
}

configure_logrotate() {
    log "Configuring log rotation"

    cat > "/etc/logrotate.d/${APP_NAME}" <<EOF
${APP_PATH}/storage/logs/*.log {
    daily
    missingok
    rotate 14
    compress
    delaycompress
    notifempty
    create 664 ${APP_USER} ${APP_USER}
    sharedscripts
}
EOF
}

configure_ssl_if_requested() {
    local should_ssl="${ENABLE_SSL}"

    if [[ "${should_ssl}" == "ask" ]]; then
        if [[ -n "${DOMAIN}" && -n "${ADMIN_EMAIL}" && ! "${DOMAIN}" =~ ^[0-9]+\.[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
            should_ssl="yes"
        else
            should_ssl="no"
        fi
    fi

    if [[ "${should_ssl}" != "yes" ]]; then
        log "SSL skipped. Configure DOMAIN and ADMIN_EMAIL later, then run certbot --nginx."
        return
    fi

    if [[ -z "${DOMAIN}" || -z "${ADMIN_EMAIL}" ]]; then
        log "SSL skipped because DOMAIN or ADMIN_EMAIL is empty."
        return
    fi

    log "Installing Let's Encrypt SSL certificate with Certbot"

    apt-get install -y certbot python3-certbot-nginx

    certbot --nginx \
        --non-interactive \
        --agree-tos \
        --redirect \
        -m "${ADMIN_EMAIL}" \
        -d "${DOMAIN}" || log "Certbot failed. Confirm DNS points to this EC2 public IP, then rerun certbot."
}

final_health_check() {
    log "Running final health checks"

    nginx -t
    systemctl is-active --quiet "php${PHP_VERSION}-fpm"
    systemctl is-active --quiet nginx
    systemctl is-active --quiet postgresql
    systemctl is-active --quiet redis-server
    systemctl is-active --quiet supervisor

    cd "${APP_PATH}"
    run_app php artisan about || true
    run_app php artisan horizon:status || true

    curl -fsS http://127.0.0.1/health >/dev/null || log "Local /health check failed. Review Nginx and Laravel logs."

    {
        echo "IAtechs Pro installation completed"
        echo "Date: $(date -Is)"
        echo "App path: ${APP_PATH}"
        echo "Domain: ${DOMAIN:-not configured}"
        echo "Database: ${DB_NAME}"
        echo "Database user: ${DB_USER}"
        echo "Filesystem disk: ${FILESYSTEM_DISK}"
        echo "Health URL: ${DOMAIN:+https://${DOMAIN}/health}"
        echo "Health URL without domain: http://$(detect_public_ip)/health"
        echo ""
        echo "Important commands:"
        echo "  sudo supervisorctl status"
        echo "  sudo supervisorctl restart ${APP_NAME}-horizon"
        echo "  cd ${APP_PATH} && sudo -u ${APP_USER} php artisan migrate --force"
        echo "  cd ${APP_PATH} && sudo -u ${APP_USER} php artisan optimize:clear"
    } | tee "/root/${APP_NAME}-install-report.txt"
}

collect_inputs() {
    if [[ ! -f "${SOURCE_PATH}/artisan" && -f "${APP_PATH}/artisan" ]]; then
        SOURCE_PATH="${APP_PATH}"
    fi

    prompt_if_empty DOMAIN "Domain for Nginx/SSL (leave empty to use public IP only): " ""
    prompt_if_empty ADMIN_EMAIL "Admin email for Let's Encrypt notices (optional): " ""
    prompt_if_empty DB_PASSWORD "PostgreSQL password for ${DB_USER} (leave empty to auto-generate): " "" "yes"
    prompt_if_empty PRODUCTION_ADMIN_EMAIL "Production admin email for ${SEEDER_CLASS} (required if RUN_SEEDERS=yes): " ""
    prompt_if_empty PRODUCTION_ADMIN_PASSWORD "Production admin password for ${SEEDER_CLASS} (required if RUN_SEEDERS=yes): " "" "yes"
    prompt_if_empty PRODUCTION_ADMIN_NAME "Production admin name (optional): " "IAtechs Super Admin"

    if [[ -z "${DB_PASSWORD}" ]]; then
        DB_PASSWORD="$(openssl rand -base64 32 | tr -d '\n')"
        log "Generated PostgreSQL password and saved it in ${SECRETS_FILE}"
    fi

    validate_identifier "${DB_NAME}" "DB_NAME"
    validate_identifier "${DB_USER}" "DB_USER"

    if [[ "${RUN_SEEDERS}" == "yes" && ( -z "${PRODUCTION_ADMIN_EMAIL}" || -z "${PRODUCTION_ADMIN_PASSWORD}" ) ]]; then
        log "RUN_SEEDERS=yes but production admin credentials are empty. Seeder will run without creating super admin."
    fi

    install -m 0600 /dev/null "${SECRETS_FILE}"
    {
        echo "DB_DATABASE=${DB_NAME}"
        echo "DB_USERNAME=${DB_USER}"
        echo "DB_PASSWORD=${DB_PASSWORD}"
        echo "PRODUCTION_ADMIN_EMAIL=${PRODUCTION_ADMIN_EMAIL}"
        echo "PRODUCTION_ADMIN_NAME=${PRODUCTION_ADMIN_NAME}"
    } > "${SECRETS_FILE}"
}

main() {
    require_root
    install -m 0600 /dev/null "${LOG_FILE}"

    log "Starting IAtechs Pro EC2 installer"
    log "Source path: ${SOURCE_PATH}"
    log "Target path: ${APP_PATH}"

    load_install_config_if_present
    collect_inputs

    install_base_system
    configure_firewall_and_ssh
    configure_swap
    install_php_composer_node
    install_postgresql
    install_redis
    prepare_project_files
    install_nginx
    configure_laravel
    configure_supervisor_and_scheduler
    configure_logrotate
    configure_ssl_if_requested
    final_health_check

    apt-get autoremove -y
    apt-get autoclean -y

    log "Installation completed successfully"
}

main "$@"
