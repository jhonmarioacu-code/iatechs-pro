<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\PermissionRegistrar;
use App\Domains\Plans\Models\Plan;
use App\Domains\Payments\Models\Payment;
use App\Domains\Subscriptions\Models\Subscription;
use App\Domains\Notifications\Mail\TransactionalNotificationMail;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('dispatches observability alerts through email and slack when degraded thresholds are detected', function (): void {
    config()->set('observability.payment_success_rate_min', 95.0);
    config()->set('observability.payment_failed_24h_alert', 1);
    config()->set('observability.pending_online_stale_minutes', 1);
    config()->set('observability.pending_online_alert', 1);
    config()->set('observability.subscriptions_past_due_alert', 1);
    config()->set('observability.subscriptions_churn_30d_max', 1.0);
    config()->set('observability.alerts', [
        'enabled' => true,
        'email_enabled' => true,
        'email_recipients' => ['obs-super-admin@example.com'],
        'slack_enabled' => true,
        'slack_webhook_url' => 'https://hooks.slack.test/ops',
        'cooldown_minutes' => 30,
        'check_interval_minutes' => 5,
    ]);

    config()->set('services.transactional_email', [
        'provider' => 'sendgrid',
        'mailer' => 'array',
    ]);

    Mail::fake();
    Http::fake([
        'https://hooks.slack.test/*' => Http::response('ok', 200),
    ]);

    $company = sec_create_company('Obs Alerts Co', 'obs-alerts-co');
    $superAdmin = sec_create_user(
        $company,
        'obs-super-admin@example.com',
        'super_admin'
    );

    $branch = sec_create_branch($company, 'OBS01');
    $customer = sec_create_customer($company, $branch, 'OBS01', 'obs-customer@example.com');
    $device = sec_create_device($company, $branch, $customer, 'OBS01');
    $invoice = sec_create_invoice(
        $company,
        $branch,
        $customer,
        sec_create_ticket($company, $branch, $customer, $device, 'OBS01'),
        'OBS01'
    );

    $failedPayment = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'OBS-FAIL');
    $failedPayment->update([
        'status' => Payment::FAILED,
        'payment_method' => 'STRIPE',
        'updated_at' => now()->subMinutes(30),
    ]);

    $stalePending = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'OBS-PENDING');
    $stalePending->update([
        'status' => Payment::PENDING,
        'payment_method' => 'MERCADOPAGO',
        'created_at' => now()->subMinutes(15),
        'updated_at' => now()->subMinutes(15),
    ]);

    $plan = Plan::query()->create([
        'uuid' => (string) Str::uuid(),
        'name' => 'Observability Plan',
        'slug' => 'observability-plan',
        'monthly_price' => 120,
        'yearly_price' => 1200,
        'status' => 'active',
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 120,
        'starts_at' => now()->subDays(20)->toDateString(),
        'ends_at' => now()->addDays(10)->toDateString(),
        'status' => 'past_due',
    ]);

    Subscription::query()->create([
        'uuid' => (string) Str::uuid(),
        'company_id' => $company->id,
        'plan_id' => $plan->id,
        'billing_cycle' => 'monthly',
        'amount' => 120,
        'starts_at' => now()->subDays(80)->toDateString(),
        'ends_at' => now()->subDays(10)->toDateString(),
        'status' => 'cancelled',
        'cancelled_at' => now()->subDays(3),
    ]);

    $exitCode = Artisan::call('iatechs:observability-alerts', [
        '--force' => true,
        '--json' => true,
    ]);

    expect($exitCode)->toBe(0);
    $result = json_decode(Artisan::output(), true, 512, JSON_THROW_ON_ERROR);

    expect($result['status'] ?? null)->toBe('degraded');
    expect((int) ($result['degraded_count'] ?? 0))->toBeGreaterThan(0);
    expect((int) ($result['dispatched_count'] ?? 0))->toBeGreaterThan(0);

    Mail::assertSent(TransactionalNotificationMail::class, function (TransactionalNotificationMail $mail): bool {
        return $mail->hasTo('obs-super-admin@example.com')
            && str_contains($mail->notification->title, '[Observability]');
    });

    Http::assertSent(static function ($request): bool {
        return $request->url() === 'https://hooks.slack.test/ops'
            && str_contains((string) $request->body(), '[IAtechs Pro][Observability]');
    });
});

it('does not dispatch observability alerts when thresholds are healthy', function (): void {
    config()->set('observability.payment_success_rate_min', 90.0);
    config()->set('observability.payment_failed_24h_alert', 10);
    config()->set('observability.pending_online_stale_minutes', 30);
    config()->set('observability.pending_online_alert', 10);
    config()->set('observability.subscriptions_past_due_alert', 10);
    config()->set('observability.subscriptions_churn_30d_max', 15.0);
    config()->set('observability.alerts', [
        'enabled' => true,
        'email_enabled' => true,
        'email_recipients' => ['healthy-super-admin@example.com'],
        'slack_enabled' => true,
        'slack_webhook_url' => 'https://hooks.slack.test/ops',
        'cooldown_minutes' => 30,
        'check_interval_minutes' => 5,
    ]);

    Mail::fake();
    Http::fake([
        'https://hooks.slack.test/*' => Http::response('ok', 200),
    ]);

    $company = sec_create_company('Obs Healthy Co', 'obs-healthy-co');
    sec_create_user($company, 'healthy-super-admin@example.com', 'super_admin');

    $exitCode = Artisan::call('iatechs:observability-alerts', [
        '--json' => true,
    ]);

    expect($exitCode)->toBe(0);
    $result = json_decode(Artisan::output(), true, 512, JSON_THROW_ON_ERROR);

    expect($result['status'] ?? null)->toBe('healthy');
    expect((int) ($result['degraded_count'] ?? -1))->toBe(0);
    expect((int) ($result['dispatched_count'] ?? -1))->toBe(0);

    Mail::assertNothingSent();
    Http::assertNothingSent();
});

it('suppresses duplicated observability alerts while cooldown is active', function (): void {
    config()->set('observability.payment_success_rate_min', 0.0);
    config()->set('observability.payment_failed_24h_alert', 1);
    config()->set('observability.pending_online_stale_minutes', 60);
    config()->set('observability.pending_online_alert', 10);
    config()->set('observability.subscriptions_past_due_alert', 10);
    config()->set('observability.subscriptions_churn_30d_max', 99.0);
    config()->set('observability.alerts', [
        'enabled' => true,
        'email_enabled' => true,
        'email_recipients' => ['cooldown-super-admin@example.com'],
        'slack_enabled' => true,
        'slack_webhook_url' => 'https://hooks.slack.test/ops',
        'cooldown_minutes' => 60,
        'check_interval_minutes' => 5,
    ]);

    config()->set('services.transactional_email', [
        'provider' => 'sendgrid',
        'mailer' => 'array',
    ]);

    Mail::fake();
    Http::fake([
        'https://hooks.slack.test/*' => Http::response('ok', 200),
    ]);

    $company = sec_create_company('Obs Cooldown Co', 'obs-cooldown-co');
    $superAdmin = sec_create_user(
        $company,
        'cooldown-super-admin@example.com',
        'super_admin'
    );

    $branch = sec_create_branch($company, 'COOL01');
    $customer = sec_create_customer($company, $branch, 'COOL01', 'cooldown-customer@example.com');
    $device = sec_create_device($company, $branch, $customer, 'COOL01');
    $invoice = sec_create_invoice(
        $company,
        $branch,
        $customer,
        sec_create_ticket($company, $branch, $customer, $device, 'COOL01'),
        'COOL01'
    );

    $failedPayment = sec_create_payment($company, $branch, $customer, $invoice, $superAdmin, 'COOL-FAIL');
    $failedPayment->update([
        'status' => Payment::FAILED,
        'payment_method' => 'STRIPE',
        'updated_at' => now()->subMinutes(10),
    ]);

    $firstExit = Artisan::call('iatechs:observability-alerts', [
        '--json' => true,
    ]);
    expect($firstExit)->toBe(0);
    $firstResult = json_decode(Artisan::output(), true, 512, JSON_THROW_ON_ERROR);

    expect($firstResult['status'] ?? null)->toBe('degraded');
    expect((int) ($firstResult['dispatched_count'] ?? 0))->toBeGreaterThan(0);

    $secondExit = Artisan::call('iatechs:observability-alerts', [
        '--json' => true,
    ]);
    expect($secondExit)->toBe(0);
    $secondResult = json_decode(Artisan::output(), true, 512, JSON_THROW_ON_ERROR);

    expect($secondResult['status'] ?? null)->toBe('degraded');
    expect((int) ($secondResult['dispatched_count'] ?? -1))->toBe(0);
    expect((int) ($secondResult['suppressed_count'] ?? 0))->toBeGreaterThan(0);
});

