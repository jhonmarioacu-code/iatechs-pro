# IAtechs Pro

# Architecture

## 08-Queues-Jobs.md

---

# Objetivo

Definir la arquitectura oficial de colas, jobs, workers y procesamiento asíncrono para IAtechs Pro, garantizando rendimiento, escalabilidad y estabilidad empresarial.

---

# Tecnologías

## Queue Driver

```text
Redis
```

---

## Queue Monitor

```text
Laravel Horizon
```

---

## Process Manager

```text
Supervisor
```

---

# Arquitectura General

```text
Usuario
    ↓

Laravel Application
    ↓

Dispatch Job
    ↓

Redis Queue
    ↓

Horizon
    ↓

Worker
    ↓

Proceso Ejecutado
```

---

# Beneficios

* Mayor velocidad.
* Mejor experiencia de usuario.
* Procesamiento masivo.
* Escalabilidad horizontal.
* Menor carga del servidor web.

---

# Componentes

```text
Jobs
Queues
Workers
Redis
Horizon
Supervisor
```

---

# Ubicación

## Jobs

```text
app/Jobs/
```

---

## Queue Config

```text
config/queue.php
```

---

## Horizon Config

```text
config/horizon.php
```

---

# Driver Principal

```env
QUEUE_CONNECTION=redis
```

---

# Redis

## Uso

```text
Queues
Cache
Sessions
Permissions
Dashboard
```

---

# Horizon

## Objetivo

Monitorear colas y workers.

---

## Dashboard

```text
https://app.iatechspro.com/horizon
```

---

# Acceso

Solo:

```text
super_admin
owner
```

---

# Colas Oficiales

## High Priority

```text
high
```

Uso:

```text
Tickets críticos
Facturación
Pagos
```

---

## Default

```text
default
```

Uso:

```text
Procesos normales
```

---

## Notifications

```text
notifications
```

Uso:

```text
Emails
SMS
WhatsApp
Push
```

---

## Reports

```text
reports
```

Uso:

```text
PDF
Excel
Exportaciones
```

---

## AI

```text
ai
```

Uso:

```text
Chat IA
Embeddings
RAG
Análisis
```

---

## Analytics

```text
analytics
```

Uso:

```text
KPIs
Métricas
Indicadores
```

---

# Estructura de Jobs

```text
app/Jobs/

GenerateInvoiceJob.php
SendEmailJob.php
SendNotificationJob.php
GenerateReportJob.php
ProcessAIRequestJob.php
GenerateEmbeddingJob.php
AnalyzeTicketJob.php
UpdateAnalyticsJob.php
```

---

# Multi-Tenant

Todos los jobs deben incluir:

```php
public int $companyId;
```

---

# Ejemplo

```php
class GenerateInvoiceJob
{
    public int $companyId;
    public int $invoiceId;
}
```

---

# Jobs de Facturación

```text
GenerateInvoiceJob
SendInvoiceJob
CalculateTaxesJob
```

---

# Jobs de Tickets

```text
AssignTechnicianJob
UpdateTicketStatusJob
GenerateTicketSummaryJob
```

---

# Jobs de Reparaciones

```text
GenerateRepairReportJob
UpdateWarrantyJob
```

---

# Jobs de Inventario

```text
UpdateStockJob
LowStockAlertJob
GeneratePurchaseSuggestionJob
```

---

# Jobs de IA

```text
ProcessAIRequestJob
GenerateEmbeddingJob
AnalyzeDocumentJob
GenerateDiagnosticJob
RunAutomationJob
```

---

# Jobs de Reportes

```text
GeneratePDFReportJob
GenerateExcelReportJob
GenerateDashboardSnapshotJob
```

---

# Jobs de Notificaciones

```text
SendEmailJob
SendSmsJob
SendWhatsAppJob
SendPushNotificationJob
```

---

# Retry Strategy

## Intentos

```text
3
```

---

## Espera

```text
60 segundos
```

---

# Configuración

```php
public $tries = 3;

public $backoff = 60;
```

---

# Failed Jobs

Tabla:

```text
failed_jobs
```

---

# Registro

Guardar:

```text
Error
Stack Trace
Tenant
Usuario
Fecha
```

---

# Horizon Supervisors

## Producción

```text
Supervisor-High
Supervisor-Default
Supervisor-Notifications
Supervisor-Reports
Supervisor-AI
Supervisor-Analytics
```

---

# Distribución Inicial

```text
High          → 4 Workers
Default       → 4 Workers
Notifications → 2 Workers
Reports       → 2 Workers
AI            → 2 Workers
Analytics     → 2 Workers
```

---

# Escalabilidad

Horizon puede aumentar workers automáticamente según carga.

---

# Supervisor

Archivo:

```text
/etc/supervisor/conf.d/iatechspro-horizon.conf
```

---

# Servicio

```bash
php artisan horizon
```

---

# Monitoreo

Registrar:

```text
Jobs Procesados
Jobs Fallidos
Tiempo Promedio
Uso Redis
Uso CPU
Uso RAM
```

---

# Eventos

```text
JobProcessed
JobFailed
JobRetried
JobQueued
```

---

# Auditoría

Registrar:

```text
Usuario
Tenant
Job
Estado
Duración
Resultado
```

---

# Seguridad

## Reglas

```text
Validar Tenant
Validar Permisos
No compartir contexto
Registrar auditoría
```

---

# Jobs Críticos

Requieren:

```text
Logs
Retry
Auditoría
Notificación
```

---

# Procesamiento IA

Flujo:

```text
Usuario
   ↓
Prompt
   ↓
AI Queue
   ↓
Worker
   ↓
Proveedor IA
   ↓
Respuesta
```

---

# Procesamiento Reportes

Flujo:

```text
Usuario
   ↓
Solicita Reporte
   ↓
Report Queue
   ↓
Generación PDF/Excel
   ↓
S3
   ↓
Notificación Usuario
```

---

# Procesamiento Notificaciones

Flujo:

```text
Evento
   ↓
Notification Queue
   ↓
Email/SMS/WhatsApp
   ↓
Registro Auditoría
```

---

# Testing

## Unit Tests

```text
JobDispatchTest
QueueConfigurationTest
RetryLogicTest
```

---

## Feature Tests

```text
InvoiceJobTest
AIJobTest
NotificationJobTest
ReportJobTest
```

---

# Reglas de Negocio

## Regla 1

Todo proceso pesado debe ejecutarse mediante Queue.

---

## Regla 2

Toda cola debe ser monitoreada por Horizon.

---

## Regla 3

Todos los jobs deben soportar reintentos.

---

## Regla 4

Todos los jobs deben respetar company_id.

---

## Regla 5

Los jobs fallidos deben registrarse.

---

## Regla 6

Los procesos IA siempre deben ejecutarse en colas dedicadas.

---

# Roadmap

## Fase 1

```text
Emails
Reportes
Notificaciones
```

---

## Fase 2

```text
Facturación
Inventario
Analytics
```

---

## Fase 3

```text
IA
Embeddings
RAG
```

---

## Fase 4

```text
Workers Distribuidos
Auto Scaling
Procesamiento Masivo
```

---

# Resultado Esperado

IAtechs Pro dispondrá de una arquitectura de colas empresarial basada en Redis, Horizon y Supervisor, capaz de procesar millones de tareas de forma segura, escalable y desacoplada, manteniendo un alto rendimiento incluso bajo cargas elevadas.
