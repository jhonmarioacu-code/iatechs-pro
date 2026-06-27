# ADR 0002: Target Architecture V2 with Next.js + NestJS

Fecha: 2026-06-27  
Estado: Propuesta aprobable (pendiente de ratificacion final CTO)

## 1. Contexto

El producto requiere evolucionar a una arquitectura preparada para:

1. Miles de usuarios concurrentes.
2. Escalado horizontal multi-tenant.
3. Separacion clara de dominios y contratos.
4. Evolucion controlada hacia microservicios.

El stack actual en produccion sigue vigente para continuidad operativa.

## 2. Decision

Se adopta como arquitectura objetivo V2:

1. Frontend: Next.js + TypeScript + TailwindCSS.
2. Backend: NestJS + TypeScript (Modular Monolith fase 1).
3. Data: PostgreSQL + Prisma.
4. Cache y sesiones: Redis.
5. Mensajeria: RabbitMQ fase 1, evaluacion Kafka fase 2.
6. Arquitectura: DDD + Clean Architecture + SOLID + CQRS + Event Driven.

Documento maestro:

- `docs/architecture/19-Target-Architecture-V2-Nextjs-Nestjs.md`

## 3. Consecuencias

1. Se habilita roadmap dual-run (plataforma actual + V2).
2. Se requieren contratos API estables y estrategia de migracion incremental.
3. Se exige disciplina de ADR para cambios de stack futuros.

## 4. Estado de convivencia

1. Produccion actual: stack vigente.
2. Nueva construccion: stack V2 objetivo.
3. Migracion por modulos, evitando big-bang rewrite.

