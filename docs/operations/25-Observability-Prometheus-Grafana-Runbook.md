# IAtechs Pro

# Operations

## 25-Observability-Prometheus-Grafana-Runbook

---

# Objetivo

Estandarizar la configuracion operativa de Prometheus, Grafana y Alertmanager para monitorear SLO/SLA de pagos y suscripciones.

---

# Alcance

Aplica a:

```text
Prometheus scrape de /api/metrics/prometheus
Reglas de alerta SLO/SLA
Enrutamiento de alertas con Alertmanager
Dashboard operativo en Grafana
```

---

# Prerrequisitos

1. Variables de observabilidad configuradas en `.env`.
2. Endpoint habilitado:

```env
OBS_EXPORTER_ENABLED=true
OBS_EXPORTER_TOKEN=<token-seguro>
OBS_EXPORTER_ALLOWED_IPS=127.0.0.1,::1,172.16.0.0/12
```

3. Docker y Compose disponibles en servidor:

```bash
docker info
docker compose version
```

4. Archivos de secretos Docker (si no se usa provision automatizado):

```bash
cp docker/observability/secrets/obs_exporter_token.example docker/observability/secrets/obs_exporter_token
cp docker/observability/secrets/slack_webhook_url.example docker/observability/secrets/slack_webhook_url
```

Alternativa automatizada (recomendada):

```text
Usar workflow Deploy con provision_observability_stack=true.
El workflow crea secretos/vars y levanta el profile observability automaticamente.
```

---

# Levantar stack de observabilidad

```bash
docker compose --profile observability up -d --no-deps prometheus alertmanager grafana
```

Servicios:

```text
Prometheus:   http://localhost:9090
Alertmanager: http://localhost:9093
Grafana:      http://localhost:3000
```

---

# Topologia de scrape

- Prometheus corre en Docker.
- La app productiva corre en host.
- Target configurado: `host.docker.internal:80`.
- Prometheus autentica contra `/api/metrics/prometheus` con `Authorization: Bearer <token>`.

---

# Validaciones obligatorias

1. Validar target en Prometheus:

```text
Status > Targets > job "iatechs_app" = UP
```

2. Validar metricas clave:

```text
iatechs_payment_success_rate_24h_percent
iatechs_subscriptions_past_due
iatechs_observability_overall_status
```

3. Validar reglas cargadas:

```text
Alerts > IAtechsPaymentSuccessRateLow, IAtechsPastDueSubscriptionsHigh, etc.
```

4. Validar dashboard Grafana:

```text
Folder: IAtechs
Dashboard: IAtechs Observability - Payments & Subscriptions
```

---

# Postdeploy checks estrictos

- Script: `deploy/observability-postdeploy-check.sh`
- Incluye:
  - health app
  - metrics endpoint protegido
  - Prometheus healthy
  - validacion de target `iatechs_app` con reintentos
  - Alertmanager healthy
  - Grafana healthy

---

# Operacion diaria

Checklist diario:

```text
Prometheus target iatechs_app en UP
Sin alertas criticas abiertas > 30 min
Dashboard sin gaps de datos
Endpoint /api/metrics/prometheus accesible solo desde IPs autorizadas
```

---

# Troubleshooting rapido

1. `401/403` en scrape:

```text
Revisar OBS_EXPORTER_TOKEN y OBS_EXPORTER_ALLOWED_IPS.
```

2. `target DOWN`:

```text
Validar Nginx/app en host y conectividad desde Prometheus a host.docker.internal:80.
```

3. Sin alertas en Slack:

```text
Revisar docker/observability/secrets/slack_webhook_url y logs de Alertmanager.
```

4. Dashboard vacio:

```text
Validar datasource "IAtechs Prometheus" y queries en Grafana Explore.
```
