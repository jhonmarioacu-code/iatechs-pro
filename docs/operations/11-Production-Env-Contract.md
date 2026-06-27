# Production Environment Contract

## Objective
Define the minimum required production environment variables and operational rules for IAtechs Pro deployments.

## Global Application
- `APP_NAME`: Product display name.
- `APP_ENV`: Must be `production`.
- `APP_DEBUG`: Must be `false`.
- `APP_URL`: Public base URL of the platform.
- `APP_KEY`: Laravel application encryption key.
- `LOG_CHANNEL`: Recommended `stack`.
- `LOG_LEVEL`: Recommended `error` in production.

## Database (Required)
- `DB_CONNECTION`: Recommended `pgsql`.
- `DB_HOST`: Database host.
- `DB_PORT`: Database port.
- `DB_DATABASE`: Database name.
- `DB_USERNAME`: Database user.
- `DB_PASSWORD`: Database password.

## Redis / Queue / Session (Required)
- `REDIS_CLIENT`: Recommended `phpredis`.
- `REDIS_HOST`: Redis host.
- `REDIS_PORT`: Redis port.
- `REDIS_PASSWORD`: Redis password or `null`.
- `CACHE_STORE`: Must be `redis` in production.
- `SESSION_DRIVER`: Must be `redis` in production.
- `QUEUE_CONNECTION`: Must be `redis` in production.

## Filesystem / Object Storage
- `FILESYSTEM_DISK`: `local` or `s3` depending on environment policy.
- `AWS_ACCESS_KEY_ID`: Required when `FILESYSTEM_DISK=s3`.
- `AWS_SECRET_ACCESS_KEY`: Required when `FILESYSTEM_DISK=s3`.
- `AWS_DEFAULT_REGION`: Required when `FILESYSTEM_DISK=s3`.
- `AWS_BUCKET`: Required when `FILESYSTEM_DISK=s3`.
- `AWS_USE_PATH_STYLE_ENDPOINT`: Optional S3 compatibility toggle.

## AI Providers
- `AZURE_OPENAI_RESPONSES_ENDPOINT`: Azure AI Responses endpoint URL.
- `AZURE_OPENAI_API_KEY`: Azure AI API key.
- `GROQ_API_KEY`: Optional fallback provider key.
- `OPENAI_API_KEY`: Optional key depending on provider strategy.
- `GEMINI_API_KEY`: Optional key depending on provider strategy.
- `OLLAMA_URL`: Optional local model endpoint.

## Mail
- `MAIL_MAILER`: `smtp`, `ses`, or `log` per environment.
- `MAIL_MAILER_TRANSACTIONAL`: dedicated mailer for transactional notifications.
- `TRANSACTIONAL_EMAIL_PROVIDER`: provider id used for delivery metadata.
- `MAIL_FROM_ADDRESS`: Sender email.
- `MAIL_FROM_NAME`: Sender display name.

## Revenue Observability (SLO/SLA)
- `OBS_PAYMENT_SUCCESS_RATE_MIN`: minimum payment success percentage.
- `OBS_PAYMENT_FAILED_24H_ALERT`: failed payments alert threshold.
- `OBS_PENDING_ONLINE_STALE_MINUTES`: stale age window for pending online payments.
- `OBS_PENDING_ONLINE_ALERT`: stale pending online threshold.
- `OBS_SUBS_PAST_DUE_ALERT`: past due subscriptions threshold.
- `OBS_SUBS_CHURN_30D_MAX`: max allowed churn percentage over 30 days.
- `OBS_EXPORTER_ENABLED`: enable `/api/metrics/prometheus`.
- `OBS_EXPORTER_TOKEN`: bearer token for metrics scrape authorization.
- `OBS_EXPORTER_ALLOWED_IPS`: comma separated allowlist for metrics endpoint. Recommended baseline for host + docker network: `127.0.0.1,::1,172.16.0.0/12`.
- `OBS_ALERTS_ENABLED`: enables scheduled observability dispatch command.
- `OBS_ALERTS_EMAIL_ENABLED`: enables email alert channel.
- `OBS_ALERTS_EMAIL_RECIPIENTS`: optional comma separated `super_admin` targets.
- `OBS_ALERTS_SLACK_ENABLED`: enables Slack channel from app dispatcher.
- `OBS_ALERTS_SLACK_WEBHOOK_URL`: Slack webhook for app dispatcher.
- `OBS_ALERTS_COOLDOWN_MINUTES`: cooldown for duplicate app alerts.
- `OBS_ALERTS_CHECK_INTERVAL_MINUTES`: scheduler interval in minutes.

## Prometheus / Grafana / Alertmanager (Docker profile `observability`)
- `OBS_EXPORTER_TOKEN_FILE`: file path consumed as Docker secret by Prometheus.
- `OBS_ALERTS_SLACK_WEBHOOK_FILE`: file path consumed as Docker secret by Alertmanager.
- `PROMETHEUS_RETENTION_TIME`: TSDB retention, default `15d`.
- `GRAFANA_ADMIN_USER`: Grafana admin user.
- `GRAFANA_ADMIN_PASSWORD`: Grafana admin password.

## Security and Secret Management Rules
- Never commit real credentials or private keys to Git.
- Keep production secrets only in server `.env` or a secret manager.
- Rotate credentials immediately after any suspected leak.
- Restrict `.env` file permissions to owner and app group.

## Validation Checklist Before Deploy
- `APP_ENV=production`
- `APP_DEBUG=false`
- Database variables point to production database
- Redis variables point to production Redis
- `QUEUE_CONNECTION=redis`
- AI endpoint and API key are configured on server
- `php artisan config:cache` runs without errors

