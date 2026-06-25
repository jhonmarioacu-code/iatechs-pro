<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Plans\Models\Plan;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    Route::middleware([
        'auth',
        'tenant',
        'subscription.active',
        'portal.access:company',
        'plan.module',
    ])->get('/_test/portal/{portal}/{module}', static function () {
        return response('ok');
    });
});

it('blocks modules not included by plan features', function (): void {
    $company = sec_create_company('Plan Gate Co', 'plan-gate-co');
    $user = sec_create_user($company, 'owner-plan@test.com', 'owner');

    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Basic No Features',
        'slug' => 'basic-no-features',
        'monthly_price' => 10,
        'yearly_price' => 100,
        'max_users' => 2,
        'max_branches' => 1,
        'max_storage_gb' => 2,
        'max_tickets' => 50,
        'ai_requests_limit' => 0,
        'trial_days' => 0,
        'has_ai' => false,
        'has_inventory' => false,
        'has_reports' => false,
        'status' => 'active',
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 10,
        'starts_at' => today(),
        'ends_at' => today()->copy()->addMonth(),
        'status' => 'active',
    ]);

    $this->actingAs($user)
        ->get('/_test/portal/company/inventory')
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/_test/portal/company/reports')
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/_test/portal/company/ai-assistant')
        ->assertForbidden();

    $this->actingAs($user)
        ->get('/_test/portal/company/customers')
        ->assertOk();
});

it('blocks access when company has no active subscription', function (): void {
    $company = sec_create_company('No Sub Co', 'no-sub-co');
    $user = sec_create_user($company, 'owner-nosub@test.com', 'owner');

    $this->actingAs($user)
        ->get('/_test/portal/company/customers')
        ->assertForbidden();
});

