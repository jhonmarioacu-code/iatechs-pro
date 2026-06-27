# Diagrams

Fecha: 2026-06-26  
Estado: Oficial

## Objetivo

Centralizar diagramas de arquitectura, flujos, dominio y operaciones para mantener trazabilidad visual del sistema.

## Tipos sugeridos

- Arquitectura (contexto, contenedores, componentes)
- Flujos de negocio por modulo
- Secuencias API y eventos
- Despliegue e infraestructura
- Seguridad y tenancy

## Convencion recomendada

- Nombre: `YYYYMMDD-area-topic-vN.ext`
- Formatos: `.md` (mermaid), `.drawio`, `.png`, `.svg`
- Todo diagrama debe referenciar el documento textual relacionado

## Regla

Si un cambio altera flujo o arquitectura, actualizar diagrama asociado en el mismo PR.

