<?php

declare(strict_types=1);

return [
    'required_files' => [
        '.env.example',
        'config/app.php',
        'config/database.php',
        'config/broadcasting.php',
        'config/queue.php',
        'config/services.php',
        'deploy/one-file-production.sh',
        'docs/operations/11-Production-Env-Contract.md',
        'docs/operations/22-Architecture-Audit-Runbook.md',
        'docs/operations/24-Realtime-Broadcast-Runbook.md',
    ],

    'required_composer_scripts' => [
        'analyse',
        'test',
        'test:arch',
        'audit:architecture',
        'validate:testing',
    ],

    'required_env_keys' => [
        'APP_NAME',
        'APP_ENV',
        'APP_KEY',
        'APP_DEBUG',
        'APP_URL',
        'LOG_CHANNEL',
        'LOG_LEVEL',
        'DB_CONNECTION',
        'DB_HOST',
        'DB_PORT',
        'DB_DATABASE',
        'DB_USERNAME',
        'DB_PASSWORD',
        'REDIS_CLIENT',
        'REDIS_HOST',
        'REDIS_PORT',
        'REDIS_PASSWORD',
        'CACHE_STORE',
        'SESSION_DRIVER',
        'QUEUE_CONNECTION',
        'BROADCAST_CONNECTION',
        'FILESYSTEM_DISK',
        'AWS_ACCESS_KEY_ID',
        'AWS_SECRET_ACCESS_KEY',
        'AWS_DEFAULT_REGION',
        'AWS_BUCKET',
        'MAIL_MAILER',
        'MAIL_FROM_ADDRESS',
        'MAIL_FROM_NAME',
        'SANCTUM_STATEFUL_DOMAINS',
        'AZURE_OPENAI_RESPONSES_ENDPOINT',
        'AZURE_OPENAI_API_KEY',
        'VITE_BROADCAST_CONNECTION',
        'VITE_REVERB_APP_KEY',
        'VITE_REVERB_HOST',
        'VITE_REVERB_PORT',
        'VITE_REVERB_SCHEME',
        'VITE_PUSHER_APP_KEY',
        'VITE_PUSHER_HOST',
        'VITE_PUSHER_PORT',
        'VITE_PUSHER_SCHEME',
        'VITE_PUSHER_APP_CLUSTER',
    ],

    'required_env_values' => [
        'APP_ENV' => 'production',
        'APP_DEBUG' => 'false',
        'DB_CONNECTION' => 'pgsql',
        'CACHE_STORE' => 'redis',
        'SESSION_DRIVER' => 'redis',
        'QUEUE_CONNECTION' => 'redis',
        'BROADCAST_CONNECTION' => 'reverb',
    ],

    'integration_env_matrix' => [
        'azure_openai' => [
            'required' => true,
            'keys' => [
                'AZURE_OPENAI_RESPONSES_ENDPOINT',
                'AZURE_OPENAI_API_KEY',
            ],
        ],
        'redis_queue_horizon' => [
            'required' => true,
            'keys' => [
                'REDIS_HOST',
                'REDIS_PORT',
                'QUEUE_CONNECTION',
                'HORIZON_PREFIX',
            ],
        ],
        'mail' => [
            'required' => true,
            'keys' => [
                'MAIL_MAILER',
                'MAIL_FROM_ADDRESS',
                'MAIL_FROM_NAME',
            ],
        ],
        'storage_s3' => [
            'required' => true,
            'keys' => [
                'FILESYSTEM_DISK',
                'AWS_ACCESS_KEY_ID',
                'AWS_SECRET_ACCESS_KEY',
                'AWS_DEFAULT_REGION',
                'AWS_BUCKET',
            ],
        ],
        'websockets_reverb' => [
            'required' => true,
            'keys' => [
                'BROADCAST_CONNECTION',
                'REVERB_APP_ID',
                'REVERB_APP_KEY',
                'REVERB_APP_SECRET',
                'REVERB_HOST',
                'REVERB_PORT',
                'REVERB_SCHEME',
                'VITE_BROADCAST_CONNECTION',
                'VITE_REVERB_APP_KEY',
                'VITE_REVERB_HOST',
                'VITE_REVERB_PORT',
                'VITE_REVERB_SCHEME',
            ],
        ],
        'pusher' => [
            'required' => false,
            'keys' => [
                'PUSHER_APP_ID',
                'PUSHER_APP_KEY',
                'PUSHER_APP_SECRET',
                'PUSHER_APP_CLUSTER',
            ],
        ],
        'mercado_pago' => [
            'required' => false,
            'keys' => [
                'MERCADOPAGO_ACCESS_TOKEN',
                'MERCADOPAGO_PUBLIC_KEY',
            ],
        ],
        'stripe' => [
            'required' => false,
            'keys' => [
                'STRIPE_KEY',
                'STRIPE_SECRET',
                'STRIPE_WEBHOOK_SECRET',
            ],
        ],
        'cloudflare' => [
            'required' => false,
            'keys' => [
                'CLOUDFLARE_API_TOKEN',
                'CLOUDFLARE_ZONE_ID',
            ],
        ],
        'oauth_jwt_passport' => [
            'required' => false,
            'keys' => [
                'OAUTH_CLIENT_ID',
                'OAUTH_CLIENT_SECRET',
                'JWT_SECRET',
                'PASSPORT_CLIENT_ID',
                'PASSPORT_CLIENT_SECRET',
            ],
        ],
    ],
];
