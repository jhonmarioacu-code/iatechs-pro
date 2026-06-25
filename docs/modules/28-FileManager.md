# Module Specification

# IAtechs Pro

## Módulo: File Manager

---

# Objetivo

Administrar de forma segura todos los archivos y documentos generados o cargados dentro de IAtechs Pro.

---

# Nombre Técnico

FileManager

---

# Tabla Principal

files

---

# Dependencias

* Companies
* Users
* Customers
* Devices
* Tickets
* Diagnostics
* Repairs
* Invoices
* Warranties
* ServiceContracts

---

# Descripción

El módulo File Manager centraliza el almacenamiento de documentos y archivos asociados a cualquier entidad del sistema.

Permitirá:

* Subir archivos.
* Descargar archivos.
* Versionar documentos.
* Gestionar permisos.
* Almacenar en AWS S3.
* Mantener auditoría documental.

---

# Tipos de Archivo

## Image

```text id="wzy5gr"
image
```

---

## PDF

```text id="ih5v9s"
pdf
```

---

## Document

```text id="m5tqrz"
document
```

---

## Spreadsheet

```text id="4rjlgm"
spreadsheet
```

---

## Video

```text id="1w6q1n"
video
```

---

## Other

```text id="vxtf0n"
other
```

---

# Tabla files

| Campo         | Tipo      |
| ------------- | --------- |
| id            | bigint    |
| company_id    | bigint    |
| uploaded_by   | bigint    |
| file_name     | string    |
| original_name | string    |
| file_type     | string    |
| mime_type     | string    |
| file_size     | bigint    |
| disk          | string    |
| path          | string    |
| checksum      | string    |
| version       | integer   |
| is_public     | boolean   |
| created_at    | timestamp |
| updated_at    | timestamp |

---

# Tabla file_relations

| Campo       | Tipo      |
| ----------- | --------- |
| id          | bigint    |
| file_id     | bigint    |
| entity_type | string    |
| entity_id   | bigint    |
| created_at  | timestamp |

---

# Tabla file_versions

| Campo          | Tipo      |
| -------------- | --------- |
| id             | bigint    |
| file_id        | bigint    |
| version_number | integer   |
| path           | string    |
| uploaded_by    | bigint    |
| created_at     | timestamp |

---

# Migración Oficial File Manager

```php
Schema::create('files', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies');

    $table->foreignId('uploaded_by')
        ->constrained('users');

    $table->string('file_name');

    $table->string('original_name');

    $table->string('file_type');

    $table->string('mime_type');

    $table->unsignedBigInteger('file_size');

    $table->string('disk')
        ->default('s3');

    $table->string('path');

    $table->string('checksum')
        ->nullable();

    $table->integer('version')
        ->default(1);

    $table->boolean('is_public')
        ->default(false);

    $table->timestamps();

    $table->softDeletes();
});
```

---

# Relaciones

## User

```php
public function uploadedBy()
{
    return $this->belongsTo(User::class, 'uploaded_by');
}
```

---

## Versions

```php
public function versions()
{
    return $this->hasMany(FileVersion::class);
}
```

---

## Relations

```php
public function relations()
{
    return $this->hasMany(FileRelation::class);
}
```

---

# Modelo

```text
app/Models/File.php
```

---

# Repository

```text
app/Repositories/FileRepository.php
```

---

# Service

```text
app/Services/FileService.php
```

---

# Responsabilidades

* Cargar archivos.
* Descargar archivos.
* Gestionar versiones.
* Gestionar permisos.
* Validar integridad.
* Organizar documentos.
* Integrarse con AWS S3.

---

# Policy

```text
FilePolicy
```

---

# Permisos

```text
files.view
files.upload
files.download
files.update
files.delete
files.share
files.manage
```

---

# Endpoints Web

```http
GET     /files
GET     /files/upload
POST    /files
GET     /files/{id}
GET     /files/{id}/download
DELETE  /files/{id}
POST    /files/{id}/version
```

---

# Endpoints API

```http
GET     /api/v1/files
POST    /api/v1/files
GET     /api/v1/files/{id}
DELETE  /api/v1/files/{id}
GET     /api/v1/files/{id}/download
```

---

# Almacenamiento

## AWS S3

```text
Producción
```

---

## Local Storage

```text
Desarrollo
```

---

## Backups

```text
S3 Glacier
```

---

# Casos de Uso

## Tickets

```text
Fotos del daño
Videos del problema
Documentos del cliente
```

---

## Diagnósticos

```text
Reportes PDF
Evidencias fotográficas
```

---

## Reparaciones

```text
Antes y después
Fotos de repuestos
```

---

## Facturación

```text
Facturas PDF
Comprobantes
```

---

## Contratos

```text
Contratos firmados
Anexos
```

---

# Flujo de Negocio

## Subida

```text
Usuario
   ↓
Selecciona Archivo
   ↓
Validación
   ↓
AWS S3
   ↓
Registro Base Datos
```

---

## Descarga

```text
Usuario
   ↓
Permisos
   ↓
Generar URL Temporal
   ↓
Descarga
```

---

# Reglas de Negocio

## Regla 1

Todo archivo pertenece a una empresa.

---

## Regla 2

Los archivos eliminados usarán Soft Delete.

---

## Regla 3

Toda descarga quedará auditada.

---

## Regla 4

Los archivos sensibles no serán públicos.

---

## Regla 5

Los archivos deberán validarse por MIME Type.

---

## Regla 6

Todo archivo tendrá checksum de integridad.

---

# Auditoría

Registrar:

```text
Archivo cargado
Archivo descargado
Archivo eliminado
Versión creada
Archivo compartido
```

---

# Eventos

```text
FileUploaded
FileDownloaded
FileDeleted
FileVersionCreated
FileShared
```

---

# Jobs

```text
GenerateFileChecksumJob
MoveFilesToArchiveJob
CleanupTemporaryFilesJob
GenerateThumbnailsJob
```

---

# Testing

## Unit Tests

```text
FileServiceTest
FileValidationTest
FileVersionTest
```

---

## Feature Tests

```text
UploadFileTest
DownloadFileTest
DeleteFileTest
ShareFileTest
```

---

# KPI del Módulo

```text
Archivos almacenados
Espacio utilizado
Descargas realizadas
Versiones creadas
Archivos compartidos
Errores de carga
```

---

# Integración con Otros Módulos

```text
Tickets
Diagnostics
Repairs
Invoices
Warranties
ServiceContracts
Notifications
AuditLogs
Analytics
```

---

# Resultado Esperado

El módulo File Manager permitirá que IAtechs Pro gestione de forma empresarial todos los documentos y archivos de la organización, garantizando seguridad, trazabilidad, control de versiones, almacenamiento escalable en AWS S3 y cumplimiento de políticas corporativas.
