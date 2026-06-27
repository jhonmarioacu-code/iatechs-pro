<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Support\Architecture\ArchitectureAuditor;
use App\Support\Architecture\ReleaseReadinessAuditor;
use App\Support\Observability\ObservabilityAlertDispatcher;

Artisan::command('iatechs:health', function () {
    $this->info('IAtechs Pro console is ready.');
});

Artisan::command('iatechs:audit-architecture {--json}', function () {
    $report = app(ArchitectureAuditor::class)->run();

    if ($this->option('json')) {
        $this->line((string) json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } else {
        $this->info('Architecture Audit Report');
        $this->line('Domains: '.$report['stats']['domains']);
        $this->line('API route files: '.$report['stats']['api_route_files']);
        $this->line('Policy files: '.$report['stats']['policy_files']);
        $this->newLine();

        if ($report['errors'] !== []) {
            $this->error('Errors:');
            foreach ($report['errors'] as $error) {
                $this->line('- '.$error);
            }
            $this->newLine();
        }

        if ($report['warnings'] !== []) {
            $this->warn('Warnings:');
            foreach ($report['warnings'] as $warning) {
                $this->line('- '.$warning);
            }
            $this->newLine();
        }
    }

    if ($report['errors'] !== []) {
        return 1;
    }

    $this->info('Architecture audit passed.');

    return 0;
});

Artisan::command('iatechs:gate-release {--json}', function () {
    $report = app(ReleaseReadinessAuditor::class)->run();

    if ($this->option('json')) {
        $this->line((string) json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    } else {
        $this->info('Release Gate Report');
        $this->line('Required files: '.$report['stats']['required_files']);
        $this->line('Required env keys: '.$report['stats']['required_env_keys']);
        $this->line('.env.example keys found: '.$report['stats']['env_example_keys']);
        $this->line('Integration checks: '.$report['stats']['integration_checks']);
        $this->newLine();

        if ($report['errors'] !== []) {
            $this->error('Errors:');
            foreach ($report['errors'] as $error) {
                $this->line('- '.$error);
            }
            $this->newLine();
        }

        if ($report['warnings'] !== []) {
            $this->warn('Warnings:');
            foreach ($report['warnings'] as $warning) {
                $this->line('- '.$warning);
            }
            $this->newLine();
        }
    }

    if ($report['errors'] !== []) {
        return 1;
    }

    $this->info('Release gate passed.');

    return 0;
});

Artisan::command('iatechs:observability-alerts {--force} {--json}', function () {
    $result = app(ObservabilityAlertDispatcher::class)->dispatch(
        (bool) $this->option('force')
    );

    if ($this->option('json')) {
        $this->line((string) json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        return 0;
    }

    $this->info('Observability Alerts Report');
    $this->line('Status: '.(string) ($result['status'] ?? 'unknown'));
    $this->line('Degraded alerts: '.(string) ($result['degraded_count'] ?? 0));
    $this->line('Dispatched alerts: '.(string) ($result['dispatched_count'] ?? 0));
    $this->line('Suppressed alerts: '.(string) ($result['suppressed_count'] ?? 0));
    $this->line('Generated at: '.(string) ($result['generated_at'] ?? now()->toIso8601String()));
    $this->newLine();

    foreach ((array) ($result['results'] ?? []) as $item) {
        $this->line(sprintf(
            '- %s [%s] dispatched=%s',
            (string) ($item['alert'] ?? 'Alert'),
            (string) ($item['severity'] ?? 'MEDIUM'),
            (bool) ($item['dispatched'] ?? false) ? 'yes' : 'no'
        ));
    }

    return 0;
});

$observabilityAlertInterval = max(
    1,
    min((int) config('observability.alerts.check_interval_minutes', 5), 60)
);

Schedule::command('iatechs:observability-alerts')
    ->cron("*/{$observabilityAlertInterval} * * * *")
    ->withoutOverlapping()
    ->onOneServer()
    ->runInBackground()
    ->when(static fn (): bool => (bool) config('observability.alerts.enabled', true));
