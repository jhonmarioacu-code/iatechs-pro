# Architecture Audit Runbook

Fecha: 2026-06-26  
Estado: Oficial

## Objetivo

Validar automaticamente que la implementacion de codigo siga la documentacion canonica de IAtechsPro.

## Comando principal

```bash
php artisan iatechs:audit-architecture
```

Opcional en JSON:

```bash
php artisan iatechs:audit-architecture --json
```

## Validaciones incluidas

1. Paridad `docs/modules*` vs `app/Domains/*`.
2. Existencia de capas obligatorias por dominio.
3. Paridad de `routes/api/v1/*` y su registro en `routes/api.php`.
4. Cobertura del contrato multi-tenant (`BelongsToCompany`) con excepciones explicitas.
5. Paridad de permisos declarados en Policies y rutas contra `PermissionSeeder`.

## Integracion con calidad

El comando queda integrado en:

```bash
composer validate:testing
composer validate:release
```

y disponible como:

```bash
composer audit:architecture
composer gate:release
```
