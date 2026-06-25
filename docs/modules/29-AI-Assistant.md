# Module Specification

# IAtechs Pro

## Módulo: AI Assistant

---

# Objetivo

Incorporar inteligencia artificial dentro de IAtechs Pro para automatizar tareas, asistir a usuarios, optimizar procesos técnicos y mejorar la experiencia del cliente.

---

# Nombre Técnico

AIAssistant

---

# Tabla Principal

ai_conversations

---

# Dependencias

* Companies
* Users
* Customers
* Tickets
* Diagnostics
* Repairs
* Inventory
* WorkOrders
* Analytics
* Notifications
* FileManager

---

# Descripción

El módulo AI Assistant funcionará como un asistente inteligente integrado en toda la plataforma IAtechs Pro.

Permitirá:

* Chat con IA.
* Diagnóstico asistido.
* Generación de respuestas.
* Resumen automático de tickets.
* Análisis de documentos.
* Automatización operativa.
* Recomendaciones empresariales.

---

# Proveedores IA

## Groq

```text
groq
```

Proveedor principal.

---

## OpenAI

```text
openai
```

Proveedor secundario.

---

## Claude

```text
claude
```

Proveedor opcional.

---

## Local LLM

```text
local
```

Modelo privado empresarial.

---

# Tabla ai_conversations

| Campo        | Tipo      |
| ------------ | --------- |
| id           | bigint    |
| company_id   | bigint    |
| user_id      | bigint    |
| title        | string    |
| provider     | string    |
| model        | string    |
| total_tokens | integer   |
| created_at   | timestamp |
| updated_at   | timestamp |

---

# Tabla ai_messages

| Campo             | Tipo      |
| ----------------- | --------- |
| id                | bigint    |
| conversation_id   | bigint    |
| role              | string    |
| content           | longText  |
| prompt_tokens     | integer   |
| completion_tokens | integer   |
| total_tokens      | integer   |
| created_at        | timestamp |

---

# Tabla ai_automations

| Campo           | Tipo      |
| --------------- | --------- |
| id              | bigint    |
| company_id      | bigint    |
| name            | string    |
| trigger_event   | string    |
| prompt_template | longText  |
| is_active       | boolean   |
| created_at      | timestamp |

---

# Migración Oficial AI Conversations

```php
Schema::create('ai_conversations', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('user_id')
        ->constrained('users');

    $table->string('title')
        ->nullable();

    $table->string('provider')
        ->default('groq');

    $table->string('model')
        ->nullable();

    $table->integer('total_tokens')
        ->default(0);

    $table->timestamps();
});
```

---

# Relaciones

## User

```php
public function user()
{
    return $this->belongsTo(User::class);
}
```

---

## Messages

```php
public function messages()
{
    return $this->hasMany(AIMessage::class);
}
```

---

# Modelo

```text
app/Models/AIConversation.php
```

---

# Repository

```text
app/Repositories/AIRepository.php
```

---

# Service

```text
app/Services/AIService.php
```

---

# Responsabilidades

* Gestionar conversaciones.
* Ejecutar prompts.
* Analizar documentos.
* Automatizar procesos.
* Generar recomendaciones.
* Resumir información.
* Optimizar operaciones.

---

# Policy

```text
AIAssistantPolicy
```

---

# Permisos

```text
ai.view
ai.chat
ai.diagnostics
ai.automation
ai.admin
ai.analytics
ai.training
```

---

# Endpoints Web

```http
GET     /ai
POST    /ai/chat
GET     /ai/conversations
GET     /ai/conversations/{id}
DELETE  /ai/conversations/{id}

GET     /ai/automations
POST    /ai/automations
```

---

# Endpoints API

```http
POST    /api/v1/ai/chat
GET     /api/v1/ai/conversations
GET     /api/v1/ai/conversations/{id}
POST    /api/v1/ai/automations
```

---

# Funcionalidades IA

## Chat Empresarial

```text
Consultas operativas
Consultas administrativas
Consultas financieras
Consultas técnicas
```

---

## Diagnóstico Inteligente

```text
Análisis de síntomas
Posibles fallas
Recomendación de reparación
Estimación de costos
```

---

## Resumen Automático

```text
Tickets
Diagnósticos
Reparaciones
Contratos
Facturas
```

---

## Generación de Contenido

```text
Correos
Cotizaciones
Respuestas automáticas
Informes técnicos
```

---

## IA para Inventario

```text
Predicción de demanda
Reabastecimiento
Productos críticos
```

---

## IA para Analytics

```text
Análisis de KPIs
Detección de anomalías
Pronósticos
Recomendaciones
```

---

# Automatizaciones IA

## Ticket Creado

```text
Nuevo Ticket
      ↓
Analizar Descripción
      ↓
Clasificar Prioridad
      ↓
Asignar Técnico
```

---

## Diagnóstico

```text
Diagnóstico
      ↓
IA
      ↓
Sugerencias
      ↓
Técnico
```

---

## Facturación

```text
Factura
      ↓
IA
      ↓
Resumen Ejecutivo
```

---

# Reglas de Negocio

## Regla 1

Toda conversación pertenece a una empresa.

---

## Regla 2

Los datos de una empresa nunca podrán mezclarse con otra.

---

## Regla 3

Toda interacción IA debe registrarse.

---

## Regla 4

Los costos por tokens deben monitorearse.

---

## Regla 5

La IA no podrá ejecutar acciones críticas sin validación humana.

---

## Regla 6

Las respuestas generadas deberán quedar auditadas.

---

# Auditoría

Registrar:

```text
Prompt ejecutado
Respuesta generada
Documento analizado
Automatización ejecutada
Proveedor utilizado
Consumo de tokens
```

---

# Eventos

```text
AIConversationCreated
AIResponseGenerated
AIAutomationExecuted
DocumentAnalyzed
PromptExecuted
```

---

# Jobs

```text
ProcessAIRequestJob
AnalyzeDocumentJob
GenerateSummaryJob
RunAutomationJob
CalculateAITokensJob
```

---

# Testing

## Unit Tests

```text
AIServiceTest
PromptBuilderTest
TokenCalculationTest
```

---

## Feature Tests

```text
ChatWithAITest
AnalyzeDocumentTest
AutomationExecutionTest
ConversationHistoryTest
```

---

# KPI del Módulo

```text
Conversaciones generadas
Tokens consumidos
Automatizaciones ejecutadas
Diagnósticos asistidos
Ahorro operativo
Tiempo reducido por proceso
```

---

# Integración con Otros Módulos

```text
Tickets
Diagnostics
Repairs
Inventory
Analytics
Reports
Notifications
AuditLogs
FileManager
WorkOrders
```

---

# Arquitectura IA Recomendada para IAtechs Pro

## Capa 1

```text
Frontend Chat IA
```

---

## Capa 2

```text
AI Gateway Service
```

---

## Capa 3

```text
Groq API
OpenAI API
Claude API
```

---

## Capa 4

```text
Prompt Engine
Context Engine
RAG Engine
```

---

## Capa 5

```text
Base de conocimiento IAtechs Pro
Documentos
Tickets
Diagnósticos
Procedimientos
```

---

# Resultado Esperado

El módulo AI Assistant permitirá que IAtechs Pro evolucione de un sistema de gestión tradicional a una plataforma inteligente impulsada por IA, capaz de asistir técnicos, optimizar operaciones, automatizar tareas y proporcionar ventajas competitivas reales a empresas de soporte técnico y servicios tecnológicos.
