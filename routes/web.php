<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HealthController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\TechnicianPortalController;
use App\Http\Controllers\CustomerPortalController;
use App\Http\Controllers\CompanyPortalController;
use App\Http\Controllers\AccountSecurityController;
use App\Domains\AIAssistant\Controllers\AIAssistantController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Admin\ObservabilityController;
use App\Http\Controllers\Admin\OperationsController;

Route::get('/', [PortalController::class, 'public'])
    ->name('public.home');

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])
        ->name('register');

    Route::get('/register/plans', [RegisterController::class, 'plans'])
        ->name('register.plans');

    Route::post('/register', [RegisterController::class, 'store'])
        ->middleware('throttle:register')
        ->name('register.store');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('throttle:login')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    Route::get('/portal', [PortalController::class, 'homeRedirect'])
        ->name('portal.home');

    Route::get('/portal/account/security', [AccountSecurityController::class, 'edit'])
        ->name('portal.account.security.edit');

    Route::post('/portal/account/security', [AccountSecurityController::class, 'update'])
        ->middleware('throttle:password-update')
        ->name('portal.account.security.update');
});

Route::middleware(['auth', 'tenant'])->prefix('portal')->group(function () {
    Route::get('/admin', [PortalController::class, 'admin'])
        ->middleware('portal.access:admin')
        ->name('portal.admin.dashboard');

    Route::get('/admin/observability', [ObservabilityController::class, 'index'])
        ->middleware('portal.access:admin')
        ->name('portal.admin.observability');

    Route::get('/admin/operations', [OperationsController::class, 'index'])
        ->middleware('portal.access:admin')
        ->name('portal.admin.operations');

    Route::post('/admin/operations/company', [OperationsController::class, 'storeCompany'])
        ->middleware('portal.access:admin')
        ->name('portal.admin.operations.company.store');

    Route::put('/admin/operations/company/{company}', [OperationsController::class, 'updateCompany'])
        ->middleware('portal.access:admin')
        ->whereNumber('company')
        ->name('portal.admin.operations.company.update');

    Route::post('/admin/operations/user', [OperationsController::class, 'storeUser'])
        ->middleware('portal.access:admin')
        ->name('portal.admin.operations.user.store');

    Route::put('/admin/operations/user/{user}', [OperationsController::class, 'updateUser'])
        ->middleware('portal.access:admin')
        ->whereNumber('user')
        ->name('portal.admin.operations.user.update');

    Route::post('/admin/operations/user/{user}/permissions', [OperationsController::class, 'syncTechnicianPermissions'])
        ->middleware('portal.access:admin')
        ->whereNumber('user')
        ->name('portal.admin.operations.user.permissions.sync');

    Route::post('/admin/operations/subscription', [OperationsController::class, 'storeSubscription'])
        ->middleware('portal.access:admin')
        ->name('portal.admin.operations.subscription.store');

    Route::put('/admin/operations/subscription/{subscription}', [OperationsController::class, 'updateSubscription'])
        ->middleware('portal.access:admin')
        ->whereNumber('subscription')
        ->name('portal.admin.operations.subscription.update');

    Route::get('/company', [CompanyPortalController::class, 'dashboard'])
        ->middleware('portal.access:company')
        ->name('portal.company.dashboard');

    Route::post('/company/technicians/{technician}/training', [CompanyPortalController::class, 'updateTechnicianTraining'])
        ->middleware('portal.access:company')
        ->whereNumber('technician')
        ->name('portal.company.technicians.training.update');

    Route::middleware(['portal.access:company', 'plan.module'])
        ->prefix('company/ai')
        ->name('portal.company.ai.')
        ->group(function () {
        Route::get('/conversations', [AIAssistantController::class, 'conversations'])
            ->defaults('portal', 'company')
            ->defaults('module', 'ai-assistant')
            ->name('conversations');

        Route::post('/chat', [AIAssistantController::class, 'chat'])
            ->defaults('portal', 'company')
            ->defaults('module', 'ai-assistant')
            ->name('chat');

        Route::get('/conversations/{conversation}/messages', [AIAssistantController::class, 'messages'])
            ->defaults('portal', 'company')
            ->defaults('module', 'ai-assistant')
            ->name('messages');
    });

    Route::get('/technician', [TechnicianPortalController::class, 'dashboard'])
        ->middleware('portal.access:technician')
        ->name('portal.technician.dashboard');

    Route::middleware('portal.access:technician')->prefix('technician')->group(function () {
        Route::get('/tickets/{ticket}', [TechnicianPortalController::class, 'showTicket'])
            ->name('portal.technician.tickets.show');

        Route::post('/tickets/{ticket}/take', [TechnicianPortalController::class, 'takeTicket'])
            ->name('portal.technician.tickets.take');

        Route::post('/tickets/{ticket}/diagnostics', [TechnicianPortalController::class, 'storeDiagnostic'])
            ->name('portal.technician.tickets.diagnostics.store');

        Route::post('/tickets/{ticket}/quotes/submit', [TechnicianPortalController::class, 'submitQuote'])
            ->name('portal.technician.tickets.quotes.submit');

        Route::post('/diagnostics/{diagnostic}/start', [TechnicianPortalController::class, 'startDiagnostic'])
            ->name('portal.technician.diagnostics.start');

        Route::post('/diagnostics/{diagnostic}/complete', [TechnicianPortalController::class, 'completeDiagnostic'])
            ->name('portal.technician.diagnostics.complete');

        Route::post('/tickets/{ticket}/repairs', [TechnicianPortalController::class, 'storeRepair'])
            ->name('portal.technician.tickets.repairs.store');

        Route::post('/repairs/{repair}/start', [TechnicianPortalController::class, 'startRepair'])
            ->name('portal.technician.repairs.start');

        Route::post('/repairs/{repair}/complete', [TechnicianPortalController::class, 'completeRepair'])
            ->name('portal.technician.repairs.complete');

        Route::post('/tickets/{ticket}/close', [TechnicianPortalController::class, 'closeTicket'])
            ->name('portal.technician.tickets.close');

        Route::post('/tickets/{ticket}/repair-assets', [TechnicianPortalController::class, 'updateRepairAssets'])
            ->name('portal.technician.tickets.repair-assets.update');

        Route::prefix('ai')->name('portal.technician.ai.')->group(function () {
            Route::get('/conversations', [AIAssistantController::class, 'conversations'])
                ->name('conversations');

            Route::post('/chat', [AIAssistantController::class, 'chat'])
                ->name('chat');

            Route::get('/conversations/{conversation}/messages', [AIAssistantController::class, 'messages'])
                ->name('messages');
        });
    });

    Route::get('/customer', [CustomerPortalController::class, 'dashboard'])
        ->middleware('portal.access:customer')
        ->name('portal.customer.dashboard');

    Route::middleware('portal.access:customer')->prefix('customer')->group(function () {
        Route::get('/tickets', [CustomerPortalController::class, 'tickets'])
            ->name('portal.customer.tickets.index');

        Route::get('/tickets/{ticket}', [CustomerPortalController::class, 'showTicket'])
            ->name('portal.customer.tickets.show');

        Route::post('/quotes/{quote}/approve', [CustomerPortalController::class, 'approveQuote'])
            ->name('portal.customer.quotes.approve');

        Route::post('/quotes/{quote}/reject', [CustomerPortalController::class, 'rejectQuote'])
            ->name('portal.customer.quotes.reject');

        Route::get('/invoices', [CustomerPortalController::class, 'invoices'])
            ->name('portal.customer.invoices.index');

        Route::get('/invoices/{invoice}', [CustomerPortalController::class, 'showInvoice'])
            ->name('portal.customer.invoices.show');

        Route::get('/invoices/{invoice}/download', [CustomerPortalController::class, 'downloadInvoice'])
            ->name('portal.customer.invoices.download');

        Route::post('/invoices/{invoice}/pay', [CustomerPortalController::class, 'payInvoice'])
            ->name('portal.customer.invoices.pay');

        Route::get('/payments/{payment}/receipt', [CustomerPortalController::class, 'paymentReceipt'])
            ->name('portal.customer.payments.receipt');

        Route::get('/marketplace', [CustomerPortalController::class, 'marketplace'])
            ->name('portal.customer.marketplace');
    });

    Route::get('/company/{module}', [PortalController::class, 'companyModuleIndex'])
        ->middleware('portal.access:company')
        ->where('module', 'customers|tickets|invoices')
        ->name('portal.company.module.index');

    Route::get('/company/{module}/create', [PortalController::class, 'companyModuleCreate'])
        ->middleware('portal.access:company')
        ->where('module', 'customers|tickets|invoices')
        ->name('portal.company.module.create');

    Route::get('/company/{module}/{id}', [PortalController::class, 'companyModuleShow'])
        ->middleware('portal.access:company')
        ->where('module', 'customers|tickets|invoices')
        ->whereNumber('id')
        ->name('portal.company.module.show');

    Route::get('/company/{module}/{id}/edit', [PortalController::class, 'companyModuleEdit'])
        ->middleware('portal.access:company')
        ->where('module', 'customers|tickets|invoices')
        ->whereNumber('id')
        ->name('portal.company.module.edit');

    Route::get('/{portal}/{module}', [PortalController::class, 'module'])
        ->middleware(['portal.access', 'plan.module'])
        ->name('portal.module');
});

Route::middleware(['auth', 'tenant', 'portal.access:admin'])->group(function () {
    Route::get('/admin/observability', [ObservabilityController::class, 'index'])
        ->name('admin.observability');

    Route::get('/admin/operations', [OperationsController::class, 'index'])
        ->name('admin.operations');
});

Route::prefix('admin')->group(base_path('routes/admin.php'));

Route::get('/health', [HealthController::class, 'health']);
