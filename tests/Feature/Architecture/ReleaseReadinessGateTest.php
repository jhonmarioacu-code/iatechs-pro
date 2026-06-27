<?php

declare(strict_types=1);

use App\Support\Architecture\ReleaseReadinessAuditor;

it('passes release readiness gate with no blocking errors', function (): void {
    /** @var array{errors: array<int, string>, warnings: array<int, string>, stats: array<string, int>} $report */
    $report = app(ReleaseReadinessAuditor::class)->run();

    expect($report)
        ->toHaveKeys(['errors', 'warnings', 'stats'])
        ->and($report['errors'])->toBeArray()->toBeEmpty()
        ->and($report['stats'])->toHaveKeys([
            'required_files',
            'required_env_keys',
            'env_example_keys',
            'integration_checks',
        ]);
});
