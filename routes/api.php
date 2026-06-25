<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;

/*
|--------------------------------------------------------------------------
| Health Check
|--------------------------------------------------------------------------
*/

Route::get('/health', [HealthController::class, 'api']);

/*
|--------------------------------------------------------------------------
| API V1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')

    ->middleware([
        'auth:sanctum',
        'tenant',
        'throttle:api-typed',
    ])

    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Core SaaS
        |--------------------------------------------------------------------------
        */

        require base_path(
            'routes/api/v1/companies.php'
        );

        require base_path(
            'routes/api/v1/plans.php'
        );

        require base_path(
            'routes/api/v1/subscriptions.php'
        );

        require base_path(
            'routes/api/v1/billings.php'
        );

        /*
        |--------------------------------------------------------------------------
        | ERP
        |--------------------------------------------------------------------------
        */

        require base_path(
            'routes/api/v1/branches.php'
        );

        require base_path(
            'routes/api/v1/customers.php'
        );

        require base_path(
            'routes/api/v1/invoices.php'
        );

        require base_path(
            'routes/api/v1/invoice-items.php'
        );

        require base_path(
            'routes/api/v1/payments.php'
        );

        require base_path(
            'routes/api/v1/purchases.php'
        );

        require base_path(
            'routes/api/v1/service-contracts.php'
        );

        require base_path(
            'routes/api/v1/work-orders.php'
        );

        require base_path(
            'routes/api/v1/technician-schedules.php'
        );

        require base_path(
            'routes/api/v1/analytics.php'
        );

        require base_path(
            'routes/api/v1/file-manager.php'
        );

        require base_path(
            'routes/api/v1/system-settings.php'
        );

        require base_path(
            'routes/api/v1/human-resources.php'
        );

        require base_path(
            'routes/api/v1/projects.php'
        );

        require base_path(
            'routes/api/v1/assets.php'
        );

        require base_path(
            'routes/api/v1/procurement.php'
        );

        require base_path(
            'routes/api/v1/document-management.php'
        );

        require base_path(
            'routes/api/v1/compliance.php'
        );

        require base_path(
            'routes/api/v1/business-intelligence.php'
        );

        /*
        |--------------------------------------------------------------------------
        | Future Modules
        |--------------------------------------------------------------------------
        */

        require base_path(
            'routes/api/v1/users.php'
        );
        require base_path(
            'routes/api/v1/devices.php'
        );
        require base_path(
            'routes/api/v1/tickets.php'
        );
        require base_path(
            'routes/api/v1/diagnostics.php'
        );
        require base_path(
            'routes/api/v1/repairs.php'
        );
        // require base_path('routes/api/v1/products.php');
        // require base_path('routes/api/v1/inventory.php');
        // require base_path('routes/api/v1/reports.php');
    });
