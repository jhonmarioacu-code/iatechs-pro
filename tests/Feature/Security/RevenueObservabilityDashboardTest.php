<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Plans\Models\Plan;
use App\Domains\Companies\Models\Company;
use App\Domains\Payments\Models\Payment;
use App\Domains\Subscriptions\Models\Subscription;
use App\Models\User;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('shows payment and subscription SLO/SLA alerts on observability dashboard', function (): void {
    config()->set('observability', [
        'payment_success_rate_min' => 95.0,
        'payment_failed_24h_alert' => 2,
        'pending_online_stale_minutes' => 1,
        'pending_online_alert' => 1,
        'subscriptions_past_due_alert' => 1,
        'subscriptions_churn_30d_max' => 1.0,
    ]);

    $company = Company::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Revenue Obs Co',
        'slug' => 'revenue-obs-co',
        'status' => Company::STATUS_ACTIVE,
        'country' => 'CO',
    ]);

    $superAdmin = User::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'name' => 'Revenue Super Admin',
        'email' => 'revenue-admin@example.com',
        'password' => 'Secret123!',
        'is_active' => true,
        'email_verified_at' => now(),
    ]);

    $role = Role::query()->firstOrCreate([
        'name' => 'super_admin',
        'guard_name' => 'web',
    ]);
    $superAdmin->assignRole($role);

    $branch = sec_create_branch($company, 'RV01');
    $customer = sec_create_customer($company, $branch, 'RV01', 'revenue-customer@example.com');
    $device = sec_create_device($company, $branch, $customer, 'RV01');
    $invoice = sec_create_invoice($company, $branch, $customer, sec_create_ticket($company, $branch, $customer, $device, 'RV01'), 'RV01');

    $completed = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'RV-COMP');
    $completed->update([
        'status' => Payment::COMPLETED,
        'payment_method' => 'STRIPE',
        'updated_at' => now()->subHours(2),
    ]);

    $failedA = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'RV-FAIL-A');
    $failedA->update([
        'status' => Payment::FAILED,
        'payment_method' => 'MERCADOPAGO',
        'updated_at' => now()->subHours(1),
    ]);

    $failedB = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'RV-FAIL-B');
    $failedB->update([
        'status' => Payment::FAILED,
        'payment_method' => 'STRIPE',
        'updated_at' => now()->subHours(1),
    ]);

    $stalePending = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'RV-PEND');
    $stalePending->update([
        'status' => Payment::PENDING,
        'payment_method' => 'STRIPE',
        'created_at' => now()->subMinutes(10),
        'updated_at' => now()->subMinutes(10),
    ]);

    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Revenue Plan',
        'slug' => 'revenue-plan',
        'monthly_price' => 100,
        'yearly_price' => 1000,
        'status' => 'active',
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 100,
        'starts_at' => now()->subDays(10)->toDateString(),
        'ends_at' => now()->addDays(20)->toDateString(),
        'status' => 'past_due',
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 100,
        'starts_at' => now()->subDays(50)->toDateString(),
        'ends_at' => now()->subDays(20)->toDateString(),
        'status' => 'cancelled',
        'cancelled_at' => now()->subDays(5),
    ]);

    $this->actingAs($superAdmin)
        ->get('/admin/observability')
        ->assertOk()
        ->assertSee('SLO/SLA Pagos y Suscripciones')
        ->assertSee('Payment Success Rate 24h')
        ->assertSee('Failed Payments 24h')
        ->assertSee('Past Due Subscriptions')
        ->assertSee('Revenue Snapshot')
        ->assertSee('HIGH');
});
