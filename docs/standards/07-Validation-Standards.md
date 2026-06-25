# IAtechs Pro

# Development Standards

## 07-Validation-Standards

---

# Objetivo

Definir el estándar oficial de validación de datos utilizado en IAtechs Pro para garantizar integridad, seguridad, consistencia y calidad de la información.

---

# Alcance

Aplica a:

```text
Web Requests

API Requests

Mobile Requests

AI Requests

Imports

Exports

Integraciones Externas
```

---

# Principio Fundamental

Todo dato recibido debe ser validado.

---

# Regla 1

Nunca confiar en datos provenientes del cliente.

---

# Regla 2

Toda entrada debe pasar por validación.

---

# Regla 3

Toda validación debe realizarse antes de llegar al Service.

---

# Flujo Oficial

```text
Request
 ↓
Validation
 ↓
DTO
 ↓
Service
 ↓
Repository
```

---

# Ubicación

Las validaciones deben implementarse mediante:

```text
FormRequest
```

---

# Ruta

```text
app/Domains/*/Requests
```

---

# Ejemplo

```text
StoreCustomerRequest

UpdateCustomerRequest

StoreTicketRequest

UpdateInvoiceRequest
```

---

# Convención de Nombre

Formato:

```text
ActionEntityRequest
```

---

# Correcto

```php
StoreCompanyRequest

UpdateCompanyRequest

StoreTicketRequest

CloseTicketRequest
```

---

# Incorrecto

```php
CompanyRequest

TicketValidation

ValidateCustomer
```

---

# Estructura Base

```php
class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }
}
```

---

# Validaciones Obligatorias

## Required

```php
'name' => [
    'required'
]
```

---

## String

```php
'name' => [
    'required',
    'string'
]
```

---

## Integer

```php
'stock' => [
    'required',
    'integer'
]
```

---

## Numeric

```php
'price' => [
    'required',
    'numeric'
]
```

---

## Email

```php
'email' => [
    'required',
    'email'
]
```

---

## URL

```php
'website' => [
    'nullable',
    'url'
]
```

---

## Boolean

```php
'is_active' => [
    'boolean'
]
```

---

## Date

```php
'start_date' => [
    'date'
]
```

---

# Longitudes

## Máximo

```php
'name' => [
    'max:255'
]
```

---

## Mínimo

```php
'password' => [
    'min:8'
]
```

---

# Valores Únicos

```php
'email' => [
    'required',
    'unique:users,email'
]
```

---

# Update

```php
Rule::unique('users')
    ->ignore($user->id)
```

---

# Relaciones

Validar existencia.

```php
'customer_id' => [
    'required',
    'exists:customers,id'
]
```

---

# Enumeraciones

Utilizar Enums.

```php
'status' => [
    new Enum(TicketStatus::class)
]
```

---

# Multi Tenant

Toda relación empresarial debe validar:

```php
company_id
```

---

# Regla

Nunca aceptar:

```php
company_id
```

desde el cliente.

---

# Correcto

```php
$companyId = tenant()->id();
```

---

# Incorrecto

```php
$request->company_id
```

---

# Validación de Archivos

## Imagen

```php
'avatar' => [
    'image',
    'max:2048'
]
```

---

## Documentos

```php
'document' => [
    'file',
    'mimes:pdf'
]
```

---

## Excel

```php
'import' => [
    'file',
    'mimes:xlsx,csv'
]
```

---

# Validación de Contraseñas

Estándar:

```php
'password' => [
    'required',
    'confirmed',
    Password::defaults()
]
```

---

# Sanitización

Antes de procesar:

```php
trim()

strip_tags()
```

cuando aplique.

---

# Transformación

Utilizar:

```php
prepareForValidation()
```

---

# Ejemplo

```php
protected function prepareForValidation()
{
    $this->merge([
        'email' => strtolower(
            $this->email
        )
    ]);
}
```

---

# Mensajes Personalizados

Método:

```php
messages()
```

---

# Ejemplo

```php
public function messages(): array
{
    return [
        'name.required' =>
            'El nombre es obligatorio.'
    ];
}
```

---

# Atributos Personalizados

Método:

```php
attributes()
```

---

# Ejemplo

```php
public function attributes(): array
{
    return [
        'tax_id' => 'NIT'
    ];
}
```

---

# DTO

Después de validar:

```text
Request
 ↓
DTO
 ↓
Service
```

---

# Ejemplo

```php
CreateCustomerDTO

CreateInvoiceDTO

CreateTicketDTO
```

---

# API Errors

Formato oficial:

```json
{
  "success": false,
  "message": "Validation failed.",
  "errors": {
    "email": [
      "The email field is required."
    ]
  }
}
```

---

# Seguridad

Nunca permitir:

```text
Mass Assignment no controlado

Campos ocultos

Campos administrativos
```

---

# Correcto

```php
protected $fillable = [
    'name',
    'email'
];
```

---

# Incorrecto

```php
protected $guarded = [];
```

---

# AI Requests

Validar:

```text
Prompt

Provider

Model

Context
```

---

# Ejemplo

```php
'prompt' => [
    'required',
    'string',
    'max:10000'
]
```

---

# Importaciones

Validar:

```text
Archivo

Formato

Tamaño

Columnas
```

---

# Ejemplo

```php
'import' => [
    'required',
    'file',
    'mimes:xlsx,csv'
]
```

---

# Testing

Toda validación debe tener pruebas.

---

# Unit Tests

```text
StoreCustomerRequestTest

StoreTicketRequestTest
```

---

# Feature Tests

```text
CreateCustomerValidationTest

CreateTicketValidationTest
```

---

# Reglas Prohibidas

Nunca:

```php
$request->all()
```

sin validación.

---

Nunca:

```php
Model::create(
    $request->all()
);
```

---

Nunca:

```php
$_POST
```

directamente.

---

Nunca:

```php
company_id
```

enviado por el cliente.

---

# Flujo Empresarial Oficial

```text
Request
 ↓
Validation
 ↓
Sanitization
 ↓
DTO
 ↓
Service
 ↓
Repository
 ↓
Database
```

---

# Resultado Esperado

Toda la información procesada por IAtechs Pro deberá estar validada, sanitizada y normalizada antes de ingresar a la lógica de negocio, garantizando seguridad, integridad de datos y cumplimiento de estándares Enterprise SaaS Multi-Tenant.
