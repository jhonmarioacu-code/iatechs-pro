#!/usr/bin/env bash

set -Eeuo pipefail

################################################################################
# IAtechsPro Enterprise Installer
# File: 01-system.sh
# Purpose:
#   - Ubuntu 24.04 LTS Base Setup
#   - System Update
#   - Essential Packages
#   - Timezone
#   - Base Utilities
#
# AWS EC2 Compatible
# Ubuntu Server 24.04 LTS
################################################################################

echo ""
echo "========================================================="
echo " IAtechsPro Enterprise Installer"
echo " Step 01 - System Preparation"
echo "========================================================="
echo ""

################################################################################
# ROOT VALIDATION
################################################################################

if [[ $EUID -ne 0 ]]; then
    echo "ERROR: Execute as root."
    echo ""
    echo "Example:"
    echo "sudo bash deploy/01-system.sh"
    exit 1
fi

################################################################################
# VARIABLES
################################################################################

SERVER_TIMEZONE="UTC"

################################################################################
# UPDATE SYSTEM
################################################################################

echo "========================================================="
echo "Updating package lists..."
echo "========================================================="
echo ""

apt-get update -y

echo ""
echo "========================================================="
echo "Upgrading operating system..."
echo "========================================================="
echo ""

DEBIAN_FRONTEND=noninteractive apt-get upgrade -y

################################################################################
# INSTALL ESSENTIAL PACKAGES
################################################################################

echo ""
echo "========================================================="
echo "Installing base packages..."
echo "========================================================="
echo ""

DEBIAN_FRONTEND=noninteractive apt-get install -y \

curl \
wget \
git \
nano \
vim \
zip \
unzip \
jq \
tree \
htop \
net-tools \
software-properties-common \
ca-certificates \
gnupg \
lsb-release \
build-essential \
apt-transport-https \
bash-completion

################################################################################
# CONFIGURE TIMEZONE
################################################################################

echo ""
echo "========================================================="
echo "Configuring timezone..."
echo "========================================================="
echo ""

timedatectl set-timezone "${SERVER_TIMEZONE}"

################################################################################
# CONFIGURE LOCALE
################################################################################

echo ""
echo "========================================================="
echo "Configuring locale..."
echo "========================================================="
echo ""

locale-gen en_US.UTF-8
update-locale LANG=en_US.UTF-8

################################################################################
# CLEAN SYSTEM
################################################################################

echo ""
echo "========================================================="
echo "Cleaning unused packages..."
echo "========================================================="
echo ""

apt-get autoremove -y
apt-get autoclean -y

################################################################################
# SYSTEM INFORMATION
################################################################################

echo ""
echo "========================================================="
echo "SYSTEM INFORMATION"
echo "========================================================="
echo ""

echo "Hostname:"
hostname

echo ""
echo "Kernel:"
uname -r

echo ""
echo "Ubuntu:"
lsb_release -d

echo ""
echo "Timezone:"
timedatectl | grep "Time zone"

echo ""
echo "Disk Usage:"
df -h /

echo ""
echo "Memory:"
free -h

################################################################################
# FINISH
################################################################################

echo ""
echo "========================================================="
echo "Step 01 Completed Successfully"
echo "========================================================="
echo ""

echo "Next Step:"
echo ""
echo "sudo bash deploy/02-security.sh"
echo ""