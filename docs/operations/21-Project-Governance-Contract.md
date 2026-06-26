# Project Governance Contract

Fecha: 2026-06-26  
Estado: Oficial

## 1. Objetivo

Establecer las reglas de gobernanza para roadmap, calidad, Definition of Done y trazabilidad documental de IAtechsPro.

## 2. Gobernanza de roadmap

- El roadmap vive en `docs/09-Roadmap.md`.
- Estado operativo vive en `docs/operations/19-Project-Status-And-Stage.md`.
- Toda iniciativa se etiqueta por dominio y prioridad de negocio.

## 3. Definition of Done (DoD) obligatoria

Una funcionalidad se considera terminada solo si:

1. Cumple arquitectura y contratos canonicos.
2. Respeta aislamiento multi-tenant (`company_id`).
3. Tiene validacion, autorizacion y manejo de errores.
4. Incluye pruebas automatizadas en verde.
5. Actualiza documentacion de modulo y tecnica.
6. Tiene evidencia de readiness para release cuando aplique.

## 4. Estrategia de pruebas

Nivel minimo por cambio:

- Unit tests de reglas de dominio
- Feature tests de flujos principales
- Pruebas de aislamiento tenant
- Pruebas de autorizacion/policies

Comandos de verificacion:

- `composer analyse`
- `composer test`
- `composer validate:testing`
- `npm run build`

## 5. Regla de documentacion como codigo

La documentacion es parte del entregable.  
No se aprueban cambios relevantes sin actualizacion de documentacion.

## 6. Control de decisiones tecnicas

- Toda decision arquitectonica de alto impacto se registra como ADR en `docs/decisions/*`.
- Las ADR deben indicar contexto, decision, consecuencias y fecha.

## 7. Criterios de release a produccion

1. Quality gates en verde.
2. Deploy reproducible y rollback documentado.
3. Health checks operativos (`/health`, `/api/health`).
4. Enforced RBAC + tenant isolation.
5. Evidencia postdeploy registrada en `docs/operations/*`.

## 8. Incumplimientos criticos (bloqueantes)

- Cross-tenant exposure.
- Logica de negocio en controller.
- Cambios sin pruebas.
- Cambios sin documentacion.
- Cambios de stack sin ADR.

