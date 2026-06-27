<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders portal shells with explicit transient UI reset and direct overlay actions', function (): void {
    $company = sec_create_company('Overlay Stability Co', 'overlay-stability-co');
    $branch = sec_create_branch($company, 'OVR01');

    $admin = sec_create_user(
        $company,
        'overlay-admin@example.com',
        'super_admin',
        ['analytics.view', 'notifications.view', 'ai.use', 'ai.view']
    );

    $owner = sec_create_user(
        $company,
        'overlay-owner@example.com',
        'owner',
        ['analytics.view', 'notifications.view', 'ai.use', 'ai.view']
    );

    $technician = sec_create_user(
        $company,
        'overlay-tech@example.com',
        'technician',
        ['tickets.view', 'diagnostics.view', 'repairs.view', 'notifications.view', 'ai.use', 'ai.view']
    );

    $customerUser = sec_create_user(
        $company,
        'overlay-customer@example.com',
        'customer',
        ['customer.portal.view', 'notifications.view']
    );
    sec_create_customer($company, $branch, 'OVR01', $customerUser->email);

    $cases = [
        [$admin, route('portal.admin.dashboard')],
        [$owner, route('portal.company.dashboard')],
        [$technician, route('portal.technician.dashboard')],
        [$customerUser, route('portal.customer.dashboard')],
    ];

    foreach ($cases as [$user, $url]) {
        $this->actingAs($user)
            ->get($url)
            ->assertOk()
            ->assertSee('x-init="resetTransientUi()"', false)
            ->assertSee('x-show="notificationsOpen"', false)
            ->assertSee('x-show="assistantPanelOpen"', false)
            ->assertSee('@click="toggleNotifications()"', false)
            ->assertSee('@click="toggleAssistantPanel()"', false)
            ->assertSee('@click="closeNotifications()"', false)
            ->assertSee('@click="closeAssistantPanel()"', false)
            ->assertDontSee('@portal-toggle-notifications.window', false)
            ->assertDontSee('@portal-toggle-assistant.window', false);
    }
});
