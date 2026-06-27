<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

/**
 * @return array<string, mixed>
 */
function realtime_channel_callbacks(): array
{
    $broadcaster = Broadcast::connection();
    $reflection = new \ReflectionClass($broadcaster);
    $property = $reflection->getProperty('channels');
    $property->setAccessible(true);

    /** @var array<string, mixed> $channels */
    $channels = $property->getValue($broadcaster);

    return $channels;
}

it('authorizes company channel only for same tenant and notifications permission', function (): void {
    $company = sec_create_company('Realtime Auth Co', 'realtime-auth-co');

    $authorizedUser = sec_create_user(
        $company,
        'realtime-authorized@example.com',
        'owner',
        ['notifications.view']
    );

    $forbiddenByPermission = sec_create_user(
        $company,
        'realtime-without-permission@example.com',
        'owner'
    );

    $otherCompany = sec_create_company('Realtime Other Co', 'realtime-other-co');
    $forbiddenByTenant = sec_create_user(
        $otherCompany,
        'realtime-other-tenant@example.com',
        'owner',
        ['notifications.view']
    );

    $channels = realtime_channel_callbacks();
    $callback = $channels['company.{companyId}.notifications'];

    expect($callback($authorizedUser, $company->id))->toBeTrue();
    expect($callback($forbiddenByPermission, $company->id))->toBeFalse();
    expect($callback($forbiddenByTenant, $company->id))->toBeFalse();
});

it('authorizes user channel only for owner user and notifications permission', function (): void {
    $company = sec_create_company('Realtime User Co', 'realtime-user-co');

    $userA = sec_create_user(
        $company,
        'realtime-user-a@example.com',
        'owner',
        ['notifications.view']
    );

    $userB = sec_create_user(
        $company,
        'realtime-user-b@example.com',
        'owner',
        ['notifications.view']
    );

    $userWithoutPermission = sec_create_user(
        $company,
        'realtime-user-no-permission@example.com',
        'owner'
    );

    $channels = realtime_channel_callbacks();
    $callback = $channels['user.{userId}.notifications'];

    expect($callback($userA, $userA->id))->toBeTrue();
    expect($callback($userA, $userB->id))->toBeFalse();
    expect($callback($userWithoutPermission, $userWithoutPermission->id))->toBeFalse();
});
