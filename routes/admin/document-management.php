<?php

declare(strict_types=1);

use App\Domains\DocumentManagement\Controllers\ManagedDocumentController;
use Illuminate\Support\Facades\Route;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])
    ->prefix('document-management')
    ->name('document-management.')
    ->group(function () {
        Route::get('/', [ManagedDocumentController::class, 'index'])->middleware('permission:document-management.view')->name('index');
        Route::post('/', [ManagedDocumentController::class, 'store'])->middleware('permission:document-management.create')->name('store');
        Route::get('/{managedDocument}', [ManagedDocumentController::class, 'show'])->middleware('permission:document-management.view')->name('show');
        Route::put('/{managedDocument}', [ManagedDocumentController::class, 'update'])->middleware('permission:document-management.update')->name('update');
        Route::delete('/{managedDocument}', [ManagedDocumentController::class, 'destroy'])->middleware('permission:document-management.delete')->name('destroy');
    });

