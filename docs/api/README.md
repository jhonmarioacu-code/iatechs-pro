# API Documentation

Fecha: 2026-06-26  
Estado: Oficial

## Objetivo

Centralizar la documentacion funcional y tecnica de la API versionada.

## Convencion de rutas

La API debe versionarse bajo `v1`:

```text
routes/
├── api.php
└── api/
    └── v1/
```

## Contrato tecnico

- Estilo REST
- Validacion con Form Requests
- Autorizacion con Policies
- Respuestas con API Resources
- Aislamiento tenant por `company_id`

## Referencias

- `docs/development/09-Technical-Implementation-Contract.md`
- `docs/architecture/05-API-Architecture.md`
- `docs/architecture/18-Canonical-Architecture-Source-Of-Truth.md`

