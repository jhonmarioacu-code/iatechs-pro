<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;
use App\Support\Architecture\ArchitectureAuditor;

it('passes documentation and architecture audit with zero errors', function () {
    $report = app(ArchitectureAuditor::class)->run();

    expect($report['errors'])->toBe([]);
    expect($report['warnings'])->toBe([]);
});

it('keeps canonical documentation entry points available', function () {
    $requiredDocs = [
        'docs/README.md',
        'docs/architecture/18-Canonical-Architecture-Source-Of-Truth.md',
        'docs/modules/00-Business-Domain-Map.md',
        'docs/development/09-Technical-Implementation-Contract.md',
        'docs/operations/21-Project-Governance-Contract.md',
        'docs/decisions/0001-postgresql-as-official-database.md',
    ];

    $missing = collect($requiredDocs)
        ->reject(fn (string $path): bool => File::exists(base_path($path)))
        ->values()
        ->all();

    expect($missing)->toBe([]);
});

