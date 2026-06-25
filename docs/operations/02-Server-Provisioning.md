# IAtechs Pro

# Operations

## 02-Server-Provisioning

---

# Objetivo

Definir el procedimiento oficial para la creación, configuración y preparación de servidores de IAtechs Pro en AWS.

---

# Alcance

Aplica a:

```text
Development

Staging

Production
```

---

# Cloud Provider Oficial

```text
Amazon Web Services (AWS)
```

---

# Servicios Utilizados

```text
EC2

RDS PostgreSQL

ElastiCache Redis

S3

CloudWatch

IAM

VPC
```

---

# Sistema Operativo Oficial

```text
Ubuntu Server 24.04 LTS
```

---

# Justificación

```text
Soporte LTS

Alta estabilidad

Compatibilidad Laravel

Compatibilidad AWS

Soporte PHP 8.4
```

---

# Tipos de Instancia

## Development

```text
t3.medium
```

---

## Staging

```text
m7i-flex.large
```

---

## Production Inicial

```text
m7i-flex.large
```

---

## Production Enterprise

```text
m7i.xlarge

m7i.2xlarge
```

---

# Especificación Recomendada

## Producción Inicial

```text
2 vCPU

8 GB RAM

100 GB SSD gp3
```

---

# Estructura AWS

```text
AWS

├── VPC
├── Public Subnet
├── Private Subnet
├── EC2
├── RDS
├── Redis
├── S3
└── CloudWatch
```

---

# Seguridad

## Security Group

Permitir:

```text
80

443

22
```

---

# SSH

Acceso únicamente mediante:

```text
SSH Key Pair
```

---

# Prohibido

```text
Password Authentication
```

---

# Firewall

Utilizar:

```text
UFW
```

---

# Reglas

```text
Allow 22

Allow 80

Allow 443
```

---

# Estructura del Proyecto

```text
/var/www/iatechs-pro
```

---

# Estructura Completa

```text
/var/www/iatechs-pro

├── current
├── releases
├── shared
└── storage
```

---

# Usuario de Aplicación

Crear:

```text
iatechs
```

---

# Prohibido

```text
Ejecutar Laravel como root
```

---

# Software Obligatorio

## Web Server

```text
Nginx
```

---

## PHP

```text
PHP 8.4

PHP-FPM
```

---

## Base de Datos

```text
PostgreSQL
```

---

## Cache

```text
Redis
```

---

## Queue

```text
Laravel Horizon
```

---

## Process Manager

```text
Supervisor
```

---

## Dependencias

```text
Git

Composer

Node.js

npm
```

---

# Nginx

Sitio oficial:

```text
iatechs-pro.conf
```

---

# Document Root

```text
/var/www/iatechs-pro/current/public
```

---

# SSL

Proveedor:

```text
Let's Encrypt
```

---

# Certificados

Gestionados mediante:

```text
Certbot
```

---

# Base de Datos

## Motor Oficial

```text
PostgreSQL
```

---

# Configuración

```text
UTF8

Timezone UTC
```

---

# Redis

Uso:

```text
Cache

Sessions

Queues

Horizon
```

---

# Horizon

Responsable de:

```text
Jobs

Notifications

Emails

AI Tasks

Reports
```

---

# Supervisor

Procesos administrados:

```text
Horizon

Schedule Worker
```

---

# Scheduler

Cron oficial:

```text
Laravel Scheduler
```

---

# Storage

Proveedor:

```text
AWS S3
```

---

# Estructura

```text
companies/

├── 1/
├── 2/
├── 3/
└── n/
```

---

# Logs

Ubicación

```text
storage/logs
```

---

# Producción

Enviar además a:

```text
CloudWatch
```

---

# Variables de Entorno

Ubicación:

```text
shared/.env
```

---

# Variables Críticas

```text
APP_KEY

DB_PASSWORD

REDIS_PASSWORD

AWS_SECRET_ACCESS_KEY

OPENAI_API_KEY
```

---

# Secrets

Gestionar mediante:

```text
AWS Secrets Manager
```

---

# Monitoreo

CloudWatch debe registrar:

```text
CPU

RAM

Disk

Network

Errors

Queues
```

---

# Backups

## PostgreSQL

```text
Automáticos

Diarios

Retención 30 días
```

---

# S3

```text
Versioning Enabled
```

---

# Health Check

Endpoint:

```text
/health
```

---

# Validaciones

```text
Database

Redis

Storage

Queues

Application
```

---

# Checklist Inicial

```text
Servidor creado

Ubuntu actualizado

Nginx instalado

PHP instalado

Composer instalado

PostgreSQL instalado

Redis instalado

Supervisor instalado

Proyecto desplegado

SSL instalado

Health Check OK
```

---

# Checklist Producción

```text
APP_DEBUG=false

SSL activo

Backups activos

CloudWatch activo

Horizon activo

Scheduler activo

Logs activos
```

---

# Escalabilidad

## Fase Inicial

```text
1 EC2

1 RDS

1 Redis
```

---

## Fase Enterprise

```text
Load Balancer

Auto Scaling

Multi AZ

Read Replicas

Redis Cluster
```

---

# Resultado Esperado

Los servidores de IAtechs Pro deberán estar configurados de forma consistente, segura y escalable, siguiendo estándares Enterprise SaaS Multi-Tenant y preparados para operar en AWS con alta disponibilidad y crecimiento progresivo.
