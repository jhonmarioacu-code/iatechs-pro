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
- `MAIL_FROM_ADDRESS`: Sender email.
- `MAIL_FROM_NAME`: Sender display name.

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

