<?php

declare(strict_types=1);

use Illuminate\Support\Facades\File;

it('keeps every domain aligned with the documented enterprise structure', function () {
    $requiredDirectories = [
        'Models',
        'Repositories',
        'Services',
        'Requests',
        'Policies',
        'Resources',
        'DTOs',
        'Actions',
        'Events',
        'Listeners',
        'Jobs',
        'Exceptions',
        'Enums',
        'Tests',
    ];

    $ignoredDomains = [
        'Shared',
    ];

    $domains = collect(File::directories(app_path('Domains')))
        ->reject(fn (string $path) => in_array(basename($path), $ignoredDomains, true));

    $missing = $domains
        ->flatMap(function (string $domainPath) use ($requiredDirectories) {
            return collect($requiredDirectories)
                ->reject(fn (string $directory) => File::isDirectory($domainPath.DIRECTORY_SEPARATOR.$directory))
                ->map(fn (string $directory) => basename($domainPath).'/'.$directory);
        })
        ->values();

    expect($missing->all())->toBe([]);
});

it('keeps App namespace imports resolvable by PSR-4 path', function () {
    $paths = [
        app_path(),
        base_path('routes'),
        database_path(),
        base_path('tests'),
    ];

    $missing = collect($paths)
        ->flatMap(fn (string $path) => File::isDirectory($path) ? File::allFiles($path) : [])
        ->filter(fn (SplFileInfo $file) => $file->getExtension() === 'php')
        ->flatMap(function (SplFileInfo $file) {
            preg_match_all('/^use\s+(App\\\\[^;]+);/m', $file->getContents(), $matches);

            return collect($matches[1])
                ->filter(function (string $class) {
                    $path = base_path(str_replace('\\', DIRECTORY_SEPARATOR, preg_replace('/^App\\\\/', 'app'.DIRECTORY_SEPARATOR, $class)).'.php');

                    return !File::exists($path);
                })
                ->map(fn (string $class) => $file->getRelativePathname().': '.$class);
        })
        ->values();

    expect($missing->all())->toBe([]);
});
