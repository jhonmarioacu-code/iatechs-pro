# Technical Audit Checklist

## Objetivo
Evaluar de forma profesional el estado real del proyecto antes del cierre definitivo.

## 1. Arquitectura
- [ ] Estructura DDD consistente por dominio.
- [ ] Dependencias entre capas respetadas.
- [ ] Sin lógica de negocio crítica en controladores.
- [ ] Policies aplicadas en endpoints sensibles.

## 2. Seguridad
- [ ] `APP_DEBUG=false` en producción.
- [ ] No existen secretos en Git.
- [ ] Rate limit en auth y endpoints críticos.
- [ ] Validación de entrada en Requests.
- [ ] Autorización por rol y permiso en rutas admin/api.

## 3. Multi-Tenant
- [ ] Filtros por `company_id` en consultas.
- [ ] Escrituras asociadas al tenant correcto.
- [ ] No existe fuga de datos entre empresas.
- [ ] Tests de aislamiento en verde.

## 4. Flujos Críticos
- [ ] Ticket -> Diagnóstico -> Cotización -> Reparación -> Cierre.
- [ ] Estados y transiciones consistentes.
- [ ] Notificaciones en puntos críticos del flujo.

## 5. AI Assistant
- [ ] Provider activo configurado (Azure/OpenAI/Groq según política).
- [ ] Contexto por rol aplicado.
- [ ] Historial de conversación aislado por usuario/empresa.
- [ ] Widget habilitado/deshabilitado por portal según permisos y plan.

## 6. Calidad de Código
- [ ] `composer validate --strict` OK.
- [ ] `phpstan` sin errores.
- [ ] Tests feature y seguridad en verde.
- [ ] Sin warnings críticos de deprecación.

## 7. Frontend y Assets
- [ ] `npm ci` exitoso.
- [ ] `npm run build` exitoso en CI.
- [ ] Vistas esenciales renderizan sin errores.

## 8. Operación
- [ ] Runbook de release actualizado.
- [ ] Verificación post-deploy documentada.
- [ ] Procedimiento de rollback probado.
- [ ] Monitoreo y alertas mínimas operativas.

## 9. Dependencias
- [ ] `composer.lock` y `package-lock.json` sincronizados.
- [ ] Sin paquetes abandonados o inseguros críticos.
- [ ] Versiones de runtime documentadas (PHP, Node, DB, Redis).

## Resultado de Auditoría
- Estado global: `APROBADO` / `CONDICIONAL` / `RECHAZADO`.
- Brechas críticas encontradas:
- Plan de corrección inmediato:
- Responsable:
- Fecha de cierre:

