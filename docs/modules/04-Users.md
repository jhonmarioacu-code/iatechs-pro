# Module Specification

# IAtechs Pro

## Módulo: Users

---

# Objetivo

Administrar los usuarios de las empresas registradas en IAtechs Pro.

Los usuarios podrán acceder a la plataforma según sus roles y permisos asignados.

---

# Nombre Técnico

Users

---

# Tabla Principal

users

---

# Dependencias

Este módulo depende de:

* Companies
* Subscriptions
* Roles & Permissions

---

# Descripción

Un usuario representa una persona con acceso al sistema.

Cada usuario pertenece obligatoriamente a una empresa.

---

# Roles del Sistema

Los roles serán administrados mediante Spatie Permission.

Roles iniciales:

```text id="g9e1ma"
Super Admin
Company Owner
Administrator
Technical Manager
Receptionist
Inventory Manager
Accountant
Technician
Customer
```

---

# Estados de Usuario

## Active

Usuario habilitado.

```text id="z1xq4t"
active
```

---

## Inactive

Usuario deshabilitado.

```text id="km9n4w"
inactive
```

---

## Suspended

Usuario suspendido.

```text id="5gf67r"
suspended
```

---

# Tabla users

| Campo             | Tipo      | Descripción   |
| ----------------- | --------- | ------------- |
| id                | bigint    | Identificador |
| company_id        | bigint    | Empresa       |
| name              | string    | Nombre        |
| email             | string    | Correo        |
| phone             | string    | Teléfono      |
| avatar            | string    | Foto          |
| password          | string    | Contraseña    |
| status            | string    | Estado        |
| last_login_at     | timestamp | Último acceso |
| email_verified_at | timestamp | Verificación  |
| created_at        | timestamp | Creación      |
| updated_at        | timestamp | Actualización |

---

# Migración Oficial

```php id="y8zq6n"
Schema::create('users', function (Blueprint $table) {

    $table->id();

    $table->foreignId('company_id')
        ->constrained('companies')
        ->cascadeOnDelete();

    $table->string('name');

    $table->string('email')->unique();

    $table->string('phone')->nullable();

    $table->string('avatar')->nullable();

    $table->string('password');

    $table->enum('status', [
        'active',
        'inactive',
        'suspended'
    ])->default('active');

    $table->timestamp('last_login_at')->nullable();

    $table->timestamp('email_verified_at')->nullable();

    $table->rememberToken();

    $table->timestamps();
});
```

---

# Modelo

Ubicación

```text id="j2n4fw"
app/Models/User.php
```

---

# Fillable

```php id="e4mq7c"
protected $fillable = [
    'company_id',
    'name',
    'email',
    'phone',
    'avatar',
    'password',
    'status'
];
```

---

# Hidden

```php id="g7tk4m"
protected $hidden = [
    'password',
    'remember_token'
];
```

---

# Casts

```php id="z6l3rq"
protected $casts = [
    'email_verified_at' => 'datetime',
    'last_login_at' => 'datetime',
    'password' => 'hashed'
];
```

---

# Relaciones

## Company

```php id="d2b4pv"
public function company()
{
    return $this->belongsTo(Company::class);
}
```

---

# Roles

```php id="v5x2kn"
use HasRoles;
```

Spatie Permission será obligatorio.

---

# Repository

Ubicación

```text id="j9w6zr"
app/Repositories/UserRepository.php
```

---

# Responsabilidades

* Crear usuarios.
* Buscar usuarios.
* Actualizar usuarios.
* Suspender usuarios.
* Restablecer contraseñas.

---

# Service

Ubicación

```text id="x3r7du"
app/Services/UserService.php
```

---

# Responsabilidades

* Crear usuario.
* Asignar roles.
* Activar usuario.
* Suspender usuario.
* Restablecer contraseña.
* Registrar accesos.

---

# Requests

## StoreUserRequest

```text id="c5t8vw"
app/Http/Requests/User
```

---

# Validaciones

```php id="p8q4ly"
return [

    'company_id' => [
        'required',
        'exists:companies,id'
    ],

    'name' => [
        'required',
        'string',
        'max:255'
    ],

    'email' => [
        'required',
        'email',
        'unique:users,email'
    ],

    'password' => [
        'required',
        'min:12'
    ]

];
```

---

# Policy

```text id="n4z8ka"
UserPolicy
```

---

# Permisos

```text id="q2m7ev"
users.view
users.create
users.update
users.delete
users.suspend
users.restore
```

---

# Autenticación

Tecnología oficial:

```text id="w8j4cp"
Laravel Sanctum
```

---

# Login

```http id="s7x2rd"
POST /login
```

---

# Logout

```http id="k9m3pa"
POST /logout
```

---

# API Login

```http id="u6q8fh"
POST /api/v1/auth/login
```

---

# API Logout

```http id="r5t2zn"
POST /api/v1/auth/logout
```

---

# Perfil Usuario

```http id="n7v6qk"
GET /api/v1/auth/me
```

---

# Endpoints Web

```http id="f3k8la"
GET     /users
GET     /users/create
POST    /users
GET     /users/{id}
GET     /users/{id}/edit
PUT     /users/{id}
DELETE  /users/{id}
```

---

# Endpoints API

```http id="d8p5rx"
GET     /api/v1/users
POST    /api/v1/users
GET     /api/v1/users/{id}
PUT     /api/v1/users/{id}
DELETE  /api/v1/users/{id}
```

---

# Casos de Uso

## Crear Usuario

```text id="z7k4cx"
Administrador
      ↓
Crear Usuario
      ↓
Asignar Rol
      ↓
Enviar Credenciales
      ↓
Usuario Activo
```

---

## Suspender Usuario

```text id="n3x8tm"
Administrador
      ↓
Suspender Usuario
      ↓
Bloquear Acceso
```

---

## Restablecer Contraseña

```text id="q5v7pd"
Usuario
      ↓
Solicitar Recuperación
      ↓
Correo
      ↓
Nueva Contraseña
```

---

# Reglas de Negocio

## Regla 1

Todo usuario debe pertenecer a una empresa.

---

## Regla 2

Toda acción debe validarse mediante permisos.

---

## Regla 3

Los usuarios suspendidos no pueden iniciar sesión.

---

## Regla 4

La contraseña mínima será de 12 caracteres.

---

## Regla 5

Los límites de usuarios dependerán del plan contratado.

---

# Auditoría

Registrar:

```text id="m4c7kv"
Login
Logout
Creación
Actualización
Cambio de rol
Suspensión
Reactivación
```

---

# Eventos

```text id="v2k9qy"
UserCreated
UserUpdated
UserSuspended
UserActivated
UserLoggedIn
```

---

# Jobs

```text id="h8n5zt"
SendWelcomeEmailJob
ResetPasswordJob
AuditUserAccessJob
```

---

# Testing

## Unit Tests

```text id="w5m2qx"
UserServiceTest
UserRepositoryTest
```

---

## Feature Tests

```text id="j6v9kr"
CreateUserTest
UpdateUserTest
SuspendUserTest
LoginTest
```

---

# KPI del Módulo

* Usuarios activos.
* Usuarios suspendidos.
* Usuarios por empresa.
* Últimos accesos.
* Crecimiento mensual de usuarios.

---

# Integración con Otros Módulos

```text id="p7n3wx"
Companies
Subscriptions
Roles & Permissions
Audit Logs
Notifications
Analytics
```

---

# Resultado Esperado

El módulo Users permitirá administrar de forma segura los accesos a IAtechs Pro, garantizando autenticación robusta, control de permisos, aislamiento multiempresa y trazabilidad completa de las actividades de los usuarios.
