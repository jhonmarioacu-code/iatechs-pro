<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\PermissionRegistrar;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    app(PermissionRegistrar::class)->forgetCachedPermissions();
});

it('returns 404 when prometheus exporter is disabled', function (): void {
    config()->set('observability.exporter', [
        'enabled' => false,
        'token' => 'metrics-token',
        'allowed_ips' => ['127.0.0.1'],
    ]);

    $this->get('/api/metrics/prometheus')
        ->assertNotFound();
});

it('rejects prometheus exporter requests without valid token', function (): void {
    config()->set('observability.exporter', [
        'enabled' => true,
        'token' => 'metrics-token',
        'allowed_ips' => ['127.0.0.1'],
    ]);

    $this->get('/api/metrics/prometheus')
        ->assertStatus(401);

    $this->withHeaders([
        'X-Metrics-Token' => 'wrong-token',
    ])->get('/api/metrics/prometheus')
        ->assertStatus(401);
});

it('enforces ip allowlist on prometheus exporter', function (): void {
    config()->set('observability.exporter', [
        'enabled' => true,
        'token' => 'metrics-token',
        'allowed_ips' => ['10.0.0.1'],
    ]);

    $this->withHeaders([
        'X-Metrics-Token' => 'metrics-token',
    ])->get('/api/metrics/prometheus')
        ->assertStatus(403);
});

it('returns prometheus metrics payload when token is valid', function (): void {
    config()->set('observability.exporter', [
        'enabled' => true,
        'token' => 'metrics-token',
        'allowed_ips' => ['127.0.0.1', '::1'],
    ]);

    Cache::put('metrics:http:requests_total', 42, now()->addHour());
    Cache::put('metrics:http:last_duration_ms', 87, now()->addHour());
    Cache::put('metrics:http:status:200', 10, now()->addHour());
    Cache::put('metrics:http:status:500', 2, now()->addHour());

    $response = $this->withHeaders([
        'Authorization' => 'Bearer metrics-token',
    ])->get('/api/metrics/prometheus');

    $response->assertOk();
    expect((string) $response->headers->get('Content-Type'))
        ->toContain('text/plain');

    $payload = (string) $response->getContent();
    expect($payload)->toContain('# HELP iatechs_http_requests_total');
    expect($payload)->toContain('iatechs_http_requests_total 42');
    expect($payload)->toContain('iatechs_http_status_responses_total{status_code="500"} 2');
    expect($payload)->toContain('# HELP iatechs_payment_success_rate_24h_percent');
    expect($payload)->toContain('# HELP iatechs_observability_threshold_payment_success_rate_min_percent');
    expect($payload)->toContain('# HELP iatechs_observability_alert_severity');
});

