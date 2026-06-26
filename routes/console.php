<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use App\Support\Architecture\ArchitectureAuditor;
use App\Support\Architecture\ReleaseReadinessAuditor;

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
