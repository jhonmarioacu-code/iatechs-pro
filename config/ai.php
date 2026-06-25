<?php

declare(strict_types=1);

return [

    'provider' => env(
        'AI_PROVIDER',
        'azure_openai'
    ),

    'model' => env(
        'AI_MODEL',
        'gpt-4.1-mini'
    ),
];
