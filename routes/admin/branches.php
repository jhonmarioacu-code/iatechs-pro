<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Domains\Branches\Controllers\BranchController;

Route::middleware([
    'auth'
])->group(function () {

    Route::get(
        '/branches',
        [BranchController::class, 'index']
    );

    Route::post(
        '/branches',
        [BranchController::class, 'store']
    );

    Route::get(
        '/branches/{branch}',
        [BranchController::class, 'show']
    );

    Route::put(
        '/branches/{branch}',
        [BranchController::class, 'update']
    );

    Route::delete(
        '/branches/{branch}',
        [BranchController::class, 'destroy']
    );

    Route::post(
        '/branches/{branch}/activate',
        [BranchController::class, 'activate']
    );

    Route::post(
        '/branches/{branch}/deactivate',
        [BranchController::class, 'deactivate']
    );
});