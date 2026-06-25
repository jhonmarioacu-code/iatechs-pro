<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('enables AI widget on admin portal when user has ai.use', function (): void {
    $company = sec_create_company('Portal AI Admin Co', 'portal-ai-admin-co');
    $admin = sec_create_user(
        $company,
        'portal-ai-admin@example.com',
        'super_admin',
        ['ai.use', 'ai.view']
    );

    $this->actingAs($admin)
        ->get(route('portal.admin.dashboard'))
        ->assertOk()
        ->assertSee('data-assistant-enabled="1"', false)
        ->assertSee('/admin/ai/conversations/__CONVERSATION__/messages')
        ->assertSee('Abrir IA Assistant');
});

it('enables AI widget on company portal when plan includes ai and user has ai.use', function (): void {
    $company = sec_create_company('Portal AI Company Co', 'portal-ai-company-co');

    $plan = \App\Domains\Plans\Models\Plan::query()->create([
        'uuid' => (string) \Illuminate\Support\Str::uuid(),
        'name' => 'Enterprise AI',
        'slug' => 'enterprise-ai-company-widget',
        'monthly_price' => 199,
        'yearly_price' => 1990,
        'trial_days' => 0,
        'status' => 'active',
        'has_ai' => true,
    ]);

    \App\Domains\Subscriptions\Models\Subscription::query()->create([
        'uuid' => (string) \Illuminate\Support\Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 199,
        'starts_at' => today()->subDay(),
        'ends_at' => today()->addMonth(),
        'status' => 'active',
    ]);

    $owner = sec_create_user(
        $company,
        'portal-ai-company@example.com',
        'owner',
        ['ai.use', 'ai.view', 'analytics.view', 'tickets.view']
    );

    $this->actingAs($owner)
        ->get(route('portal.company.dashboard'))
        ->assertOk()
        ->assertSee('data-assistant-enabled="1"', false)
        ->assertSee('/portal/company/ai/conversations/__CONVERSATION__/messages');
});

it('enables AI widget on technician portal when user has ai.use', function (): void {
    $company = sec_create_company('Portal AI Tech Co', 'portal-ai-tech-co');
    $technician = sec_create_user(
        $company,
        'portal-ai-tech@example.com',
        'technician',
        ['ai.use', 'ai.view', 'tickets.view', 'diagnostics.view', 'repairs.view']
    );

    $this->actingAs($technician)
        ->get(route('portal.technician.dashboard'))
        ->assertOk()
        ->assertSee('data-assistant-enabled="1"', false)
        ->assertSee('/portal/technician/ai/conversations/__CONVERSATION__/messages');
});

it('disables AI widget on customer portal', function (): void {
    $company = sec_create_company('Portal AI Customer Co', 'portal-ai-customer-co');
    $branch = sec_create_branch($company, 'AIC01');

    $customerUser = sec_create_user(
        $company,
        'portal-ai-customer@example.com',
        'customer',
        ['customer.portal.view']
    );

    sec_create_customer($company, $branch, 'AIC01', $customerUser->email);

    $this->actingAs($customerUser)
        ->get(route('portal.customer.dashboard'))
        ->assertOk()
        ->assertSee('data-assistant-enabled="0"', false);
});
