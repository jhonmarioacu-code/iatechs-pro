#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 02-security.sh
# Purpose:
#   - UFW Firewall
#   - Fail2Ban
#   - SSH Hardening
#   - Production Security
#
# AWS EC2 Ubuntu 24.04
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 02 - Security Configuration"
echo "========================================================="
echo ""

################################################################################
# ROOT VALIDATION
################################################################################

if [[ $EUID -ne 0 ]]; then
    echo "ERROR: Execute as root."
    echo ""
    echo "Example:"
    echo "sudo bash deploy/02-security.sh"
    exit 1
fi

################################################################################
# INSTALL SECURITY PACKAGES
################################################################################

echo ""
echo "========================================================="
echo "Installing security packages..."
echo "========================================================="
echo ""

apt-get update -y

DEBIAN_FRONTEND=noninteractive apt-get install -y \
ufw \
fail2ban

################################################################################
# UFW FIREWALL
################################################################################

echo ""
echo "========================================================="
echo "Configuring UFW..."
echo "========================================================="
echo ""

ufw --force reset

ufw default deny incoming
ufw default allow outgoing

################################################################################
# SSH
################################################################################

ufw allow 22/tcp comment "SSH"

################################################################################
# WEB
################################################################################

ufw allow 80/tcp comment "HTTP"

ufw allow 443/tcp comment "HTTPS"

################################################################################
# ENABLE UFW
################################################################################

ufw --force enable

################################################################################
# FAIL2BAN CONFIG
################################################################################

echo ""
echo "========================================================="
echo "Configuring Fail2Ban..."
echo "========================================================="
echo ""

cat > /etc/fail2ban/jail.local <<EOF
[DEFAULT]

bantime = 1h
findtime = 10m
maxretry = 5

backend = systemd

[sshd]
enabled = true
EOF

systemctl enable fail2ban
systemctl restart fail2ban

################################################################################
# SSH HARDENING
################################################################################

echo ""
echo "========================================================="
echo "Hardening SSH..."
echo "========================================================="
echo ""

SSHD_CONFIG="/etc/ssh/sshd_config"

cp "${SSHD_CONFIG}" "${SSHD_CONFIG}.backup"

sed -i 's/^#*PermitRootLogin.*/PermitRootLogin no/' "${SSHD_CONFIG}"

sed -i 's/^#*PasswordAuthentication.*/PasswordAuthentication yes/' "${SSHD_CONFIG}"

sed -i 's/^#*X11Forwarding.*/X11Forwarding no/' "${SSHD_CONFIG}"

sed -i 's/^#*MaxAuthTries.*/MaxAuthTries 5/' "${SSHD_CONFIG}"

sed -i 's/^#*ClientAliveInterval.*/ClientAliveInterval 300/' "${SSHD_CONFIG}"

sed -i 's/^#*ClientAliveCountMax.*/ClientAliveCountMax 2/' "${SSHD_CONFIG}"

systemctl restart ssh

################################################################################
# FAIL2BAN STATUS
################################################################################

echo ""
echo "========================================================="
echo "Fail2Ban Status"
echo "========================================================="
echo ""

fail2ban-client status

################################################################################
# FIREWALL STATUS
################################################################################

echo ""
echo "========================================================="
echo "Firewall Status"
echo "========================================================="
echo ""

ufw status verbose

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 02 Completed Successfully"
echo "========================================================="
echo ""

echo "Protected Services:"
echo ""

echo "✓ SSH"
echo "✓ HTTP"
echo "✓ HTTPS"
echo "✓ UFW"
echo "✓ Fail2Ban"

echo ""
echo "Next Step:"
echo ""

echo "sudo bash deploy/03-php.sh"
echo ""