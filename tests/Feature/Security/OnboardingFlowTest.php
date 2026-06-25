<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Plans\Models\Plan;
use App\Domains\Users\Models\User;
use App\Domains\Companies\Models\Company;
use App\Domains\Subscriptions\Models\Subscription;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('registers a company owner with an active subscription and redirects to company portal', function (): void {
    Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Starter',
        'slug' => 'starter',
        'monthly_price' => 49,
        'yearly_price' => 490,
        'trial_days' => 14,
        'status' => 'active',
    ]);

    Role::query()->firstOrCreate([
        'name' => 'owner',
        'guard_name' => 'web',
    ]);

    $response = $this->post('/register', [
        'account_type' => 'company',
        'company_name' => 'Acme Labs',
        'company_email' => 'ops@acme.test',
        'owner_name' => 'Owner User',
        'owner_email' => 'owner@acme.test',
        'owner_phone' => '3000000000',
        'plan_id' => Plan::query()->where('slug', 'starter')->value('id'),
        'password' => 'Secure1234',
        'password_confirmation' => 'Secure1234',
        'billing_cycle' => 'monthly',
    ]);

    $response->assertRedirect(route('portal.company.dashboard'));
    $this->assertAuthenticated();

    $company = Company::query()->where('name', 'Acme Labs')->first();
    expect($company)->not()->toBeNull();

    $user = User::query()->where('email', 'owner@acme.test')->first();
    expect($user)->not()->toBeNull();
    expect($user?->company_id)->toBe($company?->id);
    expect($user?->hasRole('owner'))->toBeTrue();

    $modelType = DB::table('model_has_roles')
        ->where('model_id', $user?->id)
        ->value('model_type');

    expect($modelType)->toBe(App\Models\User::class);

    $subscription = Subscription::query()->where('company_id', $company?->id)->first();
    expect($subscription)->not()->toBeNull();
    expect($subscription?->status)->toBe('active');
});

it('registers a technician account and redirects to technician portal', function (): void {
    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Starter Technician',
        'slug' => 'starter-technician',
        'monthly_price' => 19,
        'yearly_price' => 190,
        'trial_days' => 14,
        'status' => 'active',
    ]);

    Role::query()->firstOrCreate([
        'name' => 'technician',
        'guard_name' => 'web',
    ]);

    $response = $this->post('/register', [
        'account_type' => 'technician',
        'company_name' => 'Tech Solo',
        'company_email' => 'solo@tech.test',
        'owner_name' => 'Tech User',
        'owner_email' => 'tech@solo.test',
        'owner_phone' => '3010000000',
        'plan_id' => $plan->id,
        'password' => 'Secure1234',
        'password_confirmation' => 'Secure1234',
        'billing_cycle' => 'monthly',
    ]);

    $response->assertRedirect(route('portal.technician.dashboard'));
    $this->assertAuthenticated();

    $user = User::query()->where('email', 'tech@solo.test')->first();
    expect($user)->not()->toBeNull();
    expect($user?->hasRole('technician'))->toBeTrue();
});
