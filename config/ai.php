<?php

declare(strict_types=1);

return [

    'provider' => env(
        'AI_PROVIDER',
        'groq'
    ),

    'model' => env(
        'AI_MODEL',
        'llama-3.3-70b-versatile'
    ),
];
