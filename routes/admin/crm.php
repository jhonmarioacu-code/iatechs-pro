<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\CRM\Controllers\CRMController;

Route::middleware([
    'auth'
])
->prefix('crm')
->group(function () {

    Route::get(
        '/leads',
        [
            CRMController::class,
            'leads'
        ]
    );

    Route::get(
        '/opportunities',
        [
            CRMController::class,
            'opportunities'
        ]
    );

    Route::get(
        '/pipeline',
        [
            CRMController::class,
            'pipeline'
        ]
    );
});