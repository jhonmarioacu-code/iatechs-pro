# IAtechs Pro

# Operations

## 03-Database-Maintenance

---

# Objetivo

Definir los procedimientos oficiales para administración, optimización, monitoreo, mantenimiento y recuperación de bases de datos PostgreSQL utilizadas por IAtechs Pro.

---

# Alcance

Aplica a:

```text
Development

Staging

Production
```

---

# Motor Oficial

```text
PostgreSQL 17+
```

---

# Arquitectura

IAtechs Pro utiliza:

```text
Shared Database

Shared Schema

Multi-Tenant

Tenant Isolation by company_id
```

---

# Base de Datos Principal

```text
iatechs_pro
```

---

# Configuración Oficial

## Encoding

```text
UTF8
```

---

## Locale

```text
en_US.UTF-8
```

---

## Timezone

```text
UTC
```

---

# Reglas Generales

---

## Regla 1

Nunca trabajar directamente en producción.

---

## Regla 2

Toda modificación debe pasar por migraciones Laravel.

---

## Regla 3

No ejecutar cambios manuales sin aprobación.

---

## Regla 4

Toda tabla empresarial debe contener:

```sql
company_id
```

---

# Tablas Críticas

```text
companies

users

subscriptions

customers

tickets

diagnostics

repairs

inventory

invoices

payments

audit_logs
```

---

# Índices Obligatorios

## Company ID

```sql
CREATE INDEX idx_company_id
ON tickets(company_id);
```

---

## UUID

```sql
CREATE INDEX idx_uuid
ON customers(uuid);
```

---

## Relaciones

```sql
company_id

user_id

customer_id

ticket_id
```

---

# Monitoreo

Registrar:

```text
CPU

RAM

Connections

Queries

Locks

Disk Usage
```

---

# CloudWatch

Monitorear:

```text
DB Connections

Read IOPS

Write IOPS

CPU Utilization

Free Storage
```

---

# Consultas Lentas

Activar:

```sql
log_min_duration_statement
```

---

# Umbral

```text
500 ms
```

---

# Objetivo

```text
< 100 ms
```

para consultas normales.

---

# EXPLAIN

Analizar consultas complejas.

---

# Ejemplo

```sql
EXPLAIN ANALYZE
SELECT *
FROM tickets
WHERE company_id = 1;
```

---

# Vacuum

## Objetivo

Liberar espacio.

---

# Comando

```sql
VACUUM;
```

---

# Frecuencia

```text
Automática
```

---

# Vacuum Full

Solo cuando sea necesario.

```sql
VACUUM FULL;
```

---

# Advertencia

Puede bloquear tablas.

---

# Analyze

Actualizar estadísticas.

```sql
ANALYZE;
```

---

# Frecuencia

```text
Diaria
```

---

# Reindex

Cuando exista degradación.

```sql
REINDEX DATABASE iatechs_pro;
```

---

# Conexiones

Consultar:

```sql
SELECT *
FROM pg_stat_activity;
```

---

# Límite Inicial

```text
100 conexiones
```

---

# Producción Enterprise

```text
500+
```

---

# Bloqueos

Consultar:

```sql
SELECT *
FROM pg_locks;
```

---

# Validación

Monitorear:

```text
Deadlocks

Long Transactions

Blocked Queries
```

---

# Migraciones

Ejecutar mediante:

```bash
php artisan migrate --force
```

---

# Prohibido

```bash
php artisan migrate:fresh
```

en producción.

---

# Estrategia Safe Migration

```text
Agregar columna nullable

Actualizar datos

Actualizar código

Convertir a NOT NULL
```

---

# Backups

## Frecuencia

```text
Diario
```

---

# Retención

```text
30 días
```

---

# Tipo

```text
Full Backup

Point In Time Recovery
```

---

# Restore Testing

Frecuencia:

```text
Mensual
```

---

# Recovery

Método oficial:

```text
RDS Snapshot

PITR
```

---

# Auditoría

Registrar:

```text
Migrations

Schema Changes

Restore Operations

Failed Queries
```

---

# Multi-Tenant Validation

Verificar:

```text
Tenant Isolation

Company Scope

Cross Tenant Protection
```

---

# Integridad

Validar:

```text
Foreign Keys

Unique Constraints

Indexes

Soft Deletes
```

---

# Limpieza de Datos

Eliminar únicamente:

```text
Logs antiguos

Jobs antiguos

Cache temporal
```

---

# Nunca Eliminar

```text
Facturas

Pagos

Auditoría

Historial
```

---

# Métricas

Monitorear:

```text
Database Size

Table Growth

Index Usage

Query Time

Connection Count
```

---

# Alertas

Generar alertas cuando:

```text
CPU > 80%

Storage > 80%

Connections > 80%

Slow Queries > 500 ms
```

---

# Checklist Semanal

```text
Revisar conexiones

Revisar índices

Revisar queries lentas

Verificar backups

Verificar almacenamiento
```

---

# Checklist Mensual

```text
Restore Test

Revisión de índices

Análisis de crecimiento

Auditoría de rendimiento
```

---

# Disaster Recovery

Objetivos:

```text
RPO < 24 horas

RTO < 4 horas
```

---

# Resultado Esperado

La base de datos PostgreSQL de IAtechs Pro deberá operar de forma segura, optimizada y escalable, garantizando aislamiento Multi-Tenant, alta disponibilidad, integridad de datos y recuperación ante desastres.
