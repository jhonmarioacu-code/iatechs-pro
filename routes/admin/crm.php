<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;

use App\Domains\CRM\Controllers\CRMController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
->prefix('crm')
->group(function () {

    Route::get(
        '/leads',
        [
            CRMController::class,
            'leads'
        ]
    )->middleware('permission:crm.view');

    Route::get(
        '/opportunities',
        [
            CRMController::class,
            'opportunities'
        ]
    )->middleware('permission:crm.view');

    Route::get(
        '/pipeline',
        [
            CRMController::class,
            'pipeline'
        ]
    )->middleware('permission:crm.view');
});
