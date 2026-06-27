<?php

declare(strict_types=1);

return [
    'groq' => [

        'api_key' => env('GROQ_API_KEY'),

        'model' => env(
            'AI_MODEL',
            'llama-3.3-70b-versatile'
        ),

        'endpoint' =>
            'https://api.groq.com/openai/v1/chat/completions',
    ],

    'azure_openai' => [
        'api_key' => env('AZURE_OPENAI_API_KEY'),
        'endpoint' => env(
            'AZURE_OPENAI_RESPONSES_ENDPOINT',
            ''
        ),
        'model' => env('AI_MODEL', 'gpt-4.1-mini'),
    ],
];
