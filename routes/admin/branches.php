<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Branches\Controllers\BranchController;

Route::middleware([
    'auth',
    'tenant',
    'portal.access:admin',
])->group(function () {

    Route::get(
        '/branches',
        [BranchController::class, 'index']
)->middleware('permission:companies.view');

    Route::post(
        '/branches',
        [BranchController::class, 'store']
)->middleware('permission:companies.create');

    Route::get(
        '/branches/{branch}',
        [BranchController::class, 'show']
)->middleware('permission:companies.view');

    Route::put(
        '/branches/{branch}',
        [BranchController::class, 'update']
)->middleware('permission:companies.update');

    Route::delete(
        '/branches/{branch}',
        [BranchController::class, 'destroy']
)->middleware('permission:companies.delete');

    Route::post(
        '/branches/{branch}/activate',
        [BranchController::class, 'activate']
)->middleware('permission:companies.update');

    Route::post(
        '/branches/{branch}/deactivate',
        [BranchController::class, 'deactivate']
)->middleware('permission:companies.update');
});
