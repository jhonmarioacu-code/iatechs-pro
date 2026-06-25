<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\AIAssistant\Controllers\AIAssistantController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->prefix('ai')
->name('admin.ai.')
->group(function () {

    Route::post(
        '/chat',
        [
            AIAssistantController::class,
            'chat'
        ]
    )->middleware('permission:ai.use')
        ->name('chat');

    Route::get(
        '/conversations',
        [
            AIAssistantController::class,
            'conversations'
        ]
    )->middleware('permission:ai.view')
        ->name('conversations');

    Route::get(
        '/conversations/{conversation}/messages',
        [
            AIAssistantController::class,
            'messages',
        ]
    )->middleware('permission:ai.view')
        ->name('messages');
});
