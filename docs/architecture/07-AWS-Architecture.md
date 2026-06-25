# IAtechs Pro

# Architecture

## 07-AWS-Architecture.md

---

# Objetivo

Definir la arquitectura cloud oficial de IAtechs Pro sobre AWS para garantizar rendimiento, escalabilidad, seguridad, disponibilidad y crecimiento empresarial.

---

# Cloud Provider

## Plataforma Principal

```text
Amazon Web Services (AWS)
```

---

# Arquitectura General

```text
Usuarios
    ↓

CloudFront
    ↓

Application Load Balancer
    ↓

EC2 Auto Scaling Group
    ↓

Laravel 12 + Nginx + PHP 8.4
    ↓

Redis
    ↓

PostgreSQL RDS
    ↓

S3
```

---

# Infraestructura Inicial (MVP)

## EC2

```text
Ubuntu Server 24.04 LTS
```

---

## Instancia Recomendada

```text
m7i-flex.large

2 vCPU
8 GB RAM
```

---

## Uso

```text
Nginx
PHP-FPM
Laravel
Supervisor
Horizon
```

---

# Infraestructura Producción

## Aplicación

```text
EC2 Auto Scaling Group
```

---

## Balanceador

```text
Application Load Balancer (ALB)
```

---

# Red

## VPC

```text
Production VPC
```

---

## Subredes

```text
Public Subnet A
Public Subnet B

Private Subnet A
Private Subnet B
```

---

# Distribución

## Pública

```text
ALB
NAT Gateway
```

---

## Privada

```text
EC2
Redis
PostgreSQL
```

---

# Base de Datos

## Servicio

```text
Amazon RDS PostgreSQL
```

---

## Versión

```text
PostgreSQL 17
```

---

## Configuración Inicial

```text
Multi-AZ
Automated Backups
Encryption Enabled
```

---

## Escalabilidad

```text
Read Replicas
```

---

# Caché

## Servicio

```text
Amazon ElastiCache Redis
```

---

## Uso

```text
Sesiones
Permisos
Configuraciones
Dashboard
Queues
```

---

# Archivos

## Servicio

```text
Amazon S3
```

---

# Estructura

```text
companies/

company-1/
company-2/
company-3/
```

---

# Carpetas

```text
tickets/
invoices/
contracts/
documents/
avatars/
```

---

# Correos

## Servicio

```text
Amazon SES
```

---

# Uso

```text
Notificaciones
Facturas
Tickets
Alertas
```

---

# Mensajería

## Servicio

```text
Amazon SNS
```

---

# Uso

```text
SMS
Alertas
Eventos
```

---

# CDN

## Servicio

```text
Amazon CloudFront
```

---

# Beneficios

```text
Menor Latencia
Mayor Velocidad
Cache Global
```

---

# DNS

## Servicio

```text
Route 53
```

---

# Dominio

```text
iatechspro.com
api.iatechspro.com
app.iatechspro.com
```

---

# Monitoreo

## Servicio

```text
Amazon CloudWatch
```

---

# Métricas

```text
CPU
RAM
Disco
Errores
Latencia
Queries
```

---

# Logs

## CloudWatch Logs

```text
Laravel Logs
Nginx Logs
System Logs
Queue Logs
```

---

# Seguridad

## Security Groups

### ALB

```text
80
443
```

---

### EC2

```text
22
80
443
```

---

### PostgreSQL

```text
5432
```

Acceso únicamente desde EC2.

---

### Redis

```text
6379
```

Acceso únicamente desde EC2.

---

# Certificados SSL

## Servicio

```text
AWS Certificate Manager
```

---

# Certificados

```text
*.iatechspro.com
```

---

# Backups

## Base de Datos

```text
Automáticos diarios
```

---

## Retención

```text
30 días
```

---

## Archivos

```text
Versionado S3
```

---

# Recuperación

## Disaster Recovery

```text
RDS Snapshots
S3 Versioning
CloudFormation
```

---

# Escalabilidad

## Horizontal

```text
Más EC2
Más Redis
Read Replicas
```

---

## Vertical

```text
Más CPU
Más RAM
Más IOPS
```

---

# Arquitectura Laravel

## Servicios

```text
Nginx
PHP 8.4
PHP-FPM
Composer
Supervisor
Redis
Horizon
```

---

# Workers

## Supervisor

```text
Email Jobs
Report Jobs
AI Jobs
Notification Jobs
```

---

# Horizon

```text
Redis Queue Monitoring
```

---

# CI/CD

## Repositorio

```text
GitHub
```

---

## Pipeline

```text
GitHub Actions
```

---

# Flujo

```text
Push
 ↓
Tests
 ↓
Build
 ↓
Deploy
 ↓
EC2
```

---

# Ambientes

## Local

```text
VS Code
Windows
PHP 8.3+
```

---

## Development

```text
AWS EC2 DEV
```

---

## Staging

```text
AWS EC2 STAGING
```

---

## Production

```text
AWS EC2 PROD
```

---

# IA Infrastructure

## Providers

```text
Groq
OpenAI
Claude
```

---

# Integración

```text
API Gateway Interno
```

---

# Costos Iniciales Estimados

## MVP

```text
1 EC2
1 RDS
1 Redis
1 S3
1 SES
```

---

## Escala Empresarial

```text
ALB
ASG
Multi-AZ
Read Replicas
CloudFront
```

---

# Monitoreo Operacional

Registrar:

```text
Usuarios Activos
Empresas Activas
Tickets Procesados
Uso IA
Facturación
Errores
```

---

# Reglas de Negocio

## Regla 1

Toda información empresarial debe estar cifrada en tránsito y en reposo.

---

## Regla 2

Los servicios críticos deben ejecutarse en subredes privadas.

---

## Regla 3

La base de datos nunca debe exponerse a Internet.

---

## Regla 4

Todos los backups deben ser automáticos.

---

## Regla 5

Toda infraestructura debe ser monitoreada.

---

## Regla 6

La plataforma debe soportar escalamiento horizontal.

---

# Roadmap Infraestructura

## Fase 1

```text
EC2
PostgreSQL
Redis
S3
SES
```

---

## Fase 2

```text
ALB
CloudFront
Auto Scaling
```

---

## Fase 3

```text
Multi-AZ
Read Replicas
Disaster Recovery
```

---

## Fase 4

```text
Kubernetes (EKS)
Microservicios
Multi-Región
```

---

# Resultado Esperado

IAtechs Pro operará sobre una infraestructura AWS Enterprise segura, escalable y altamente disponible, preparada para miles de empresas, millones de registros y futuras capacidades avanzadas de inteligencia artificial.
