<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Notifications\Mail\TransactionalNotificationMail;
use App\Domains\Notifications\Models\Notification;
use App\Domains\Notifications\Services\NotificationService;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('delivers email notifications with transactional provider metadata', function (): void {
    config()->set('services.transactional_email', [
        'provider' => 'sendgrid',
        'mailer' => 'array',
    ]);

    Mail::fake();

    $company = sec_create_company('Transactional Mail Co', 'transactional-mail-co');
    $user = sec_create_user(
        $company,
        'mail-owner@example.com',
        'owner',
        ['notifications.create']
    );

    /** @var NotificationService $service */
    $service = app(NotificationService::class);

    $notification = $service->create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Factura emitida',
        'message' => 'Tu factura mensual fue generada correctamente.',
        'type' => 'BILLING',
        'channel' => 'EMAIL',
        'recipient' => 'cliente@example.com',
        'subject' => 'Factura IAtechs Pro',
    ]);

    Mail::assertSent(TransactionalNotificationMail::class, function (TransactionalNotificationMail $mail): bool {
        return $mail->hasTo('cliente@example.com')
            && $mail->notification->title === 'Factura emitida';
    });

    $fresh = Notification::query()->findOrFail($notification->id);
    expect($fresh->status)->toBe('DELIVERED');
    expect($fresh->delivered_at)->not->toBeNull();
    expect($fresh->data['email_delivery']['provider'] ?? null)->toBe('SENDGRID');
    expect($fresh->data['email_delivery']['mailer'] ?? null)->toBe('array');
});

it('uses user email as fallback recipient when notification recipient is empty', function (): void {
    config()->set('services.transactional_email', [
        'provider' => 'ses',
        'mailer' => 'array',
    ]);

    Mail::fake();

    $company = sec_create_company('Fallback Mail Co', 'fallback-mail-co');
    $user = sec_create_user(
        $company,
        'fallback-user@example.com',
        'owner',
        ['notifications.create']
    );

    /** @var NotificationService $service */
    $service = app(NotificationService::class);

    $notification = $service->create([
        'company_id' => $company->id,
        'user_id' => $user->id,
        'title' => 'Alerta de seguridad',
        'message' => 'Se detecto un nuevo inicio de sesion.',
        'type' => 'SECURITY',
        'channel' => 'EMAIL',
        'subject' => 'Nueva actividad en tu cuenta',
    ]);

    Mail::assertSent(TransactionalNotificationMail::class, function (TransactionalNotificationMail $mail): bool {
        return $mail->hasTo('fallback-user@example.com');
    });

    $fresh = Notification::query()->findOrFail($notification->id);
    expect($fresh->status)->toBe('DELIVERED');
    expect($fresh->data['email_delivery']['recipient'] ?? null)->toBe('fallback-user@example.com');
});
