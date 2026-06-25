<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\AIAssistant\Controllers\AIAssistantController;

Route::middleware([
    'auth'
])->prefix('ai')
->group(function () {

    Route::post(
        '/chat',
        [
            AIAssistantController::class,
            'chat'
        ]
    );

    Route::get(
        '/conversations',
        [
            AIAssistantController::class,
            'conversations'
        ]
    );
});