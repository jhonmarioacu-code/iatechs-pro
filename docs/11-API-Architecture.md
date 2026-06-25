# Reglas de Negocio

# IAtechs Pro

## Plataforma Empresarial Inteligente para Gestión de Servicios Técnicos

---

# Objetivo

Definir las reglas operativas, comerciales, financieras y de seguridad que gobiernan el funcionamiento de IAtechs Pro.

Estas reglas son obligatorias y garantizan consistencia, integridad de datos y cumplimiento de procesos.

---

# Dominio Core SaaS

## Regla 1

Una empresa debe tener una suscripción activa para acceder al sistema.

### Validación

```text
subscription.status = active
```

---

## Regla 2

Una empresa suspendida no puede iniciar sesión.

### Estados válidos

```text
active
suspended
cancelled
```

---

## Regla 3

Cada empresa solo puede acceder a sus propios datos.

### Restricción

```text
company_id obligatorio
```

---

## Regla 4

No se puede eliminar una empresa que tenga operaciones registradas.

---

# Dominio Usuarios

## Regla 5

El correo electrónico debe ser único por usuario.

---

## Regla 6

Un usuario debe pertenecer a una empresa.

---

## Regla 7

Un usuario puede tener múltiples roles.

---

## Regla 8

Los permisos prevalecen sobre los roles.

---

## Regla 9

Un usuario inactivo no puede autenticarse.

---

# Dominio CRM

## Regla 10

Un lead puede convertirse una sola vez en cliente.

---

## Regla 11

No se puede crear una oportunidad sin cliente asociado.

---

## Regla 12

Un cliente debe tener información mínima obligatoria.

### Requerido

```text
Nombre
Documento
Teléfono
```

---

# Dominio Equipos

## Regla 13

Todo equipo debe pertenecer a un cliente.

---

## Regla 14

El serial no puede duplicarse dentro de la misma empresa.

---

## Regla 15

Todo equipo debe tener una categoría asignada.

---

# Dominio Tickets

## Regla 16

Todo ticket debe estar asociado a:

```text
Cliente
Equipo
Sucursal
Empresa
```

---

## Regla 17

El número de ticket debe ser único.

### Ejemplo

```text
TKT-2026-000001
```

---

## Regla 18

Estados válidos del ticket:

```text
received
diagnosis
quotation
approved
repair
quality_control
ready
delivered
cancelled
```

---

## Regla 19

No se puede cerrar un ticket sin entrega registrada.

---

# Dominio Diagnósticos

## Regla 20

Todo diagnóstico debe estar asociado a un ticket.

---

## Regla 21

Solo técnicos autorizados pueden emitir diagnósticos.

---

## Regla 22

Un ticket debe tener al menos un diagnóstico antes de generar presupuesto.

---

# Dominio Presupuestos

## Regla 23

Todo presupuesto debe derivarse de un diagnóstico.

---

## Regla 24

No se puede iniciar reparación sin presupuesto aprobado.

---

## Regla 25

Estados válidos:

```text
draft
sent
approved
rejected
expired
```

---

## Regla 26

Una aprobación debe registrar:

```text
Fecha
Usuario
Canal
```

---

# Dominio Reparaciones

## Regla 27

Toda reparación requiere una orden de trabajo.

---

## Regla 28

Una orden de trabajo debe tener técnico asignado.

---

## Regla 29

Toda actividad técnica debe quedar registrada.

---

## Regla 30

No se puede marcar una reparación como completada sin registrar solución.

---

# Dominio Control de Calidad

## Regla 31

Toda reparación debe pasar por control de calidad.

---

## Regla 32

El técnico que ejecutó la reparación no puede aprobar su propio control de calidad.

---

# Dominio Garantías

## Regla 33

La garantía inicia con la entrega del equipo.

---

## Regla 34

Solo reparaciones completadas pueden generar garantía.

---

## Regla 35

Una garantía vencida no puede ser reclamada.

---

# Dominio Inventario

## Regla 36

El inventario nunca puede quedar negativo.

---

## Regla 37

Toda entrada o salida debe generar movimiento Kardex.

---

## Regla 38

Todo producto debe pertenecer a una categoría.

---

## Regla 39

El SKU debe ser único por empresa.

---

## Regla 40

Los repuestos usados en reparaciones deben descontarse automáticamente.

---

# Dominio Compras

## Regla 41

Toda orden de compra requiere proveedor.

---

## Regla 42

No se puede recibir mercancía sin orden de compra.

---

## Regla 43

Toda recepción actualiza inventario automáticamente.

---

# Dominio Finanzas

## Regla 44

Toda factura debe tener cliente.

---

## Regla 45

No se puede facturar un ticket cancelado.

---

## Regla 46

Toda factura debe tener numeración única.

---

## Regla 47

Todo pago debe estar asociado a una factura.

---

## Regla 48

Una factura pagada no puede volver a estado pendiente.

---

# Dominio Portal Cliente

## Regla 49

Un cliente solo puede visualizar su propia información.

---

## Regla 50

Un cliente no puede modificar diagnósticos técnicos.

---

## Regla 51

La aprobación de presupuestos debe quedar auditada.

---

# Dominio Field Service

## Regla 52

Todo servicio debe tener dirección válida.

---

## Regla 53

Toda visita técnica debe registrar evidencia.

---

## Regla 54

Toda visita finalizada requiere firma del cliente.

---

# Dominio Inteligencia Artificial

## Regla 55

La IA no puede modificar registros directamente.

---

## Regla 56

Toda recomendación IA debe registrarse en auditoría.

---

## Regla 57

Las respuestas IA son sugerencias y no decisiones automáticas.

---

# Dominio Analytics

## Regla 58

Los indicadores deben calcularse únicamente con datos válidos.

---

## Regla 59

Los reportes financieros deben considerar únicamente documentos aprobados.

---

# Dominio Auditoría

## Regla 60

Toda acción crítica debe registrarse.

---

## Eventos Auditables

```text
Login
Logout
Creación
Actualización
Eliminación
Aprobaciones
Facturación
Pagos
Cambios de Roles
```

---

# Seguridad General

## Regla 61

Toda petición requiere autenticación.

---

## Regla 62

Toda autorización debe validarse mediante roles y permisos.

---

## Regla 63

Las contraseñas deben almacenarse cifradas.

---

## Regla 64

Las sesiones expiradas deben cerrarse automáticamente.

---

## Regla 65

Los intentos fallidos de acceso deben registrarse.

---

# Reglas Multiempresa

## Regla 66

Toda entidad de negocio debe contener:

```text
company_id
```

---

## Regla 67

Los usuarios nunca podrán consultar registros de otra empresa.

---

## Regla 68

Los reportes siempre estarán filtrados por empresa.

---

# Reglas de Escalabilidad

## Regla 69

Los módulos deben funcionar de forma desacoplada.

---

## Regla 70

Toda integración externa deberá implementarse mediante servicios dedicados.

---

# Resultado Esperado

IAtechs Pro deberá garantizar que todas las operaciones técnicas, comerciales, financieras y administrativas se ejecuten bajo reglas de negocio estrictas, asegurando integridad de datos, trazabilidad, seguridad y escalabilidad empresarial.
