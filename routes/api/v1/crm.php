<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\CRM\Controllers\CRMController;

Route::prefix('crm')
    ->name('crm.')
    ->group(function () {
        Route::get('/leads', [CRMController::class, 'leads'])
            ->middleware('permission:crm.view')
            ->name('leads');

        Route::get('/opportunities', [CRMController::class, 'opportunities'])
            ->middleware('permission:crm.view')
            ->name('opportunities');

        Route::get('/pipeline', [CRMController::class, 'pipeline'])
            ->middleware('permission:crm.view')
            ->name('pipeline');
    });

