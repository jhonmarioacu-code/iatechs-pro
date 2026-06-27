<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\Provider as SocialProvider;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    config()->set('services.google', [
        'client_id' => 'google-client',
        'client_secret' => 'google-secret',
        'redirect' => 'http://localhost/auth/google/callback',
    ]);
});

it('redirects guest users to google oauth provider', function (): void {
    $provider = \Mockery::mock(SocialProvider::class);
    $provider->shouldReceive('redirect')
        ->once()
        ->andReturn(redirect()->away('https://accounts.google.com/o/oauth2/auth'));

    Socialite::shouldReceive('driver')
        ->once()
        ->with('google')
        ->andReturn($provider);

    $this->get(route('auth.social.redirect', ['provider' => 'google']))
        ->assertRedirect('https://accounts.google.com/o/oauth2/auth');
});

it('authenticates an existing active user through google callback', function (): void {
    $company = sec_create_company('OAuth Co', 'oauth-co');
    $user = sec_create_user($company, 'oauth-owner@example.com', 'owner');

    $socialUser = \Mockery::mock();
    $socialUser->shouldReceive('getEmail')->andReturn('oauth-owner@example.com');
    $socialUser->shouldReceive('getId')->andReturn('google-user-123');

    $provider = \Mockery::mock(SocialProvider::class);
    $provider->shouldReceive('user')
        ->once()
        ->andReturn($socialUser);

    Socialite::shouldReceive('driver')
        ->once()
        ->with('google')
        ->andReturn($provider);

    $this->get(route('auth.social.callback', ['provider' => 'google']))
        ->assertRedirect(route('portal.company.dashboard'));

    $this->assertAuthenticatedAs($user->fresh());

    expect($user->fresh()->social_provider)->toBe('google');
    expect($user->fresh()->social_provider_id)->toBe('google-user-123');
});

it('rejects social callback when email is not linked to an active account', function (): void {
    $socialUser = \Mockery::mock();
    $socialUser->shouldReceive('getEmail')->andReturn('not-found@example.com');
    $socialUser->shouldReceive('getId')->andReturn('external-id');

    $provider = \Mockery::mock(SocialProvider::class);
    $provider->shouldReceive('user')
        ->once()
        ->andReturn($socialUser);

    Socialite::shouldReceive('driver')
        ->once()
        ->with('google')
        ->andReturn($provider);

    $this->get(route('auth.social.callback', ['provider' => 'google']))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors('email');

    $this->assertGuest();
});

it('returns not found for unsupported social providers', function (): void {
    $this->get('/auth/invalid-provider/redirect')
        ->assertNotFound();
});
