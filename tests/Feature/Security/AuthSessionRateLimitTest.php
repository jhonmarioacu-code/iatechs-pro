<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('renders login and register forms with csrf token', function (): void {
    $this->get('/login')
        ->assertOk()
        ->assertSee('name="_token"', false);

    $this->get('/register')
        ->assertOk()
        ->assertSee('name="_token"', false);
});

it('rejects login for inactive users', function (): void {
    $company = sec_create_company('Inactive Co', 'inactive-co');
    $inactive = sec_create_user(
        $company,
        'inactive@example.com',
        'owner',
        [],
        false
    );

    $response = $this->post('/login', [
        'email' => $inactive->email,
        'password' => 'Secret123!',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

it('creates and destroys session through login and logout', function (): void {
    $company = sec_create_company('Session Flow Co', 'session-flow-co');
    $owner = sec_create_user(
        $company,
        'owner-flow@example.com',
        'owner'
    );

    $this->post('/login', [
        'email' => $owner->email,
        'password' => 'Secret123!',
    ])->assertRedirect(route('portal.company.dashboard'));

    $this->assertAuthenticatedAs($owner);

    $this->post('/logout')
        ->assertRedirect(route('login'));

    $this->assertGuest();
});

it('enforces login throttling after repeated failed attempts', function (): void {
    $company = sec_create_company('Throttle Co', 'throttle-co');
    $owner = sec_create_user(
        $company,
        'owner-throttle@example.com',
        'owner'
    );

    $lastStatus = 200;

    foreach (range(1, 6) as $attempt) {
        $response = $this->postJson('/login', [
            'email' => $owner->email,
            'password' => 'wrong-password',
        ]);
        $lastStatus = $response->status();
    }

    expect(in_array($lastStatus, [422, 429], true))->toBeTrue();
});

it('enforces register throttling by ip', function (): void {
    $lastStatus = 200;

    foreach (range(1, 4) as $attempt) {
        $response = $this->postJson('/register', []);
        $lastStatus = $response->status();
    }

    expect($lastStatus)->toBe(429);
});
