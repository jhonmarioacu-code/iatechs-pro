# ADR 0001 - PostgreSQL as Official Database

Fecha: 2026-06-26  
Estado: Aprobada

## Contexto

IAtechsPro se define como plataforma SaaS Enterprise Multi-Tenant para miles de empresas.  
Se requiere un motor de base de datos con alta integridad, buen rendimiento en consultas complejas, indices robustos y capacidades avanzadas para evolucion de producto.

## Decision

PostgreSQL se establece como motor oficial de base de datos para entornos de produccion y arquitectura objetivo del producto.

## Motivos principales

- Mejor encaje para SaaS enterprise multi-tenant
- Excelente manejo de indices y consultas complejas
- Soporte JSONB para escenarios hibridos
- Alta integridad y consistencia transaccional
- Escalabilidad y madurez operativa

## Consecuencias

1. Todo diseno de migraciones y consultas de negocio debe ser compatible con PostgreSQL.
2. El deploy de referencia usa PostgreSQL + Redis.
3. Cambios que propongan otro motor requieren nueva ADR y evaluacion de impacto.
4. Ambientes de testing pueden usar configuraciones auxiliares si no alteran la direccion oficial de producto.

## Referencias

- `docs/README.md`
- `docs/architecture/18-Canonical-Architecture-Source-Of-Truth.md`
- `docs/development/09-Technical-Implementation-Contract.md`
- `DEPLOYMENT.md`

