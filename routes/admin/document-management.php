<?php

declare(strict_types=1);

use App\Domains\DocumentManagement\Controllers\ManagedDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])
    ->prefix('document-management')
    ->name('document-management.')
    ->group(function () {
        Route::get('/', [ManagedDocumentController::class, 'index'])->name('index');
        Route::post('/', [ManagedDocumentController::class, 'store'])->name('store');
        Route::get('/{managedDocument}', [ManagedDocumentController::class, 'show'])->name('show');
        Route::put('/{managedDocument}', [ManagedDocumentController::class, 'update'])->name('update');
        Route::delete('/{managedDocument}', [ManagedDocumentController::class, 'destroy'])->name('destroy');
    });
