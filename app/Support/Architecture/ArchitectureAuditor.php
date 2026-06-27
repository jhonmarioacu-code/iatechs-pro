<?php

declare(strict_types=1);

namespace App\Support\Architecture;

use SplFileInfo;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ArchitectureAuditor
{
    public function run(): array
    {
        $errors = [];
        $warnings = [];

        $errors = array_merge($errors, $this->auditDomainDocumentationParity());
        $errors = array_merge($errors, $this->auditLayerCompleteness());
        $errors = array_merge($errors, $this->auditTenantTraitCoverage());
        $errors = array_merge($errors, $this->auditPermissionParity());
        $warnings = array_merge($warnings, $this->auditUnmappedRouteFiles());

        return [
            'errors' => array_values(array_unique($errors)),
            'warnings' => array_values(array_unique($warnings)),
            'stats' => [
                'domains' => count((array) config('architecture.domains', [])),
                'api_route_files' => count(File::files(base_path('routes/api/v1'))),
                'policy_files' => count(
                    collect(File::allFiles(app_path('Domains')))
                        ->filter(fn (SplFileInfo $file): bool => Str::endsWith($file->getFilename(), 'Policy.php'))
                ),
            ],
        ];
    }

    /**
     * @return array<int, string>
     */
    protected function auditDomainDocumentationParity(): array
    {
        $issues = [];
        $domains = (array) config('architecture.domains', []);
        $apiBootstrap = File::get(base_path('routes/api.php'));

        foreach ($domains as $domain => $metadata) {
            $domainPath = app_path('Domains'.DIRECTORY_SEPARATOR.$domain);

            if (! File::isDirectory($domainPath)) {
                $issues[] = "Domain not found: {$domainPath}";
            }

            foreach ((array) Arr::get($metadata, 'docs', []) as $docPath) {
                if (! File::exists(base_path($docPath))) {
                    $issues[] = "Missing domain documentation: {$docPath}";
                }
            }

            foreach ((array) Arr::get($metadata, 'api_routes', []) as $routeFile) {
                $routePath = base_path('routes/api/v1/'.$routeFile);

                if (! File::exists($routePath)) {
                    $issues[] = "Missing API v1 route file: routes/api/v1/{$routeFile}";
                    continue;
                }

                $needle = "routes/api/v1/{$routeFile}";
                if (! Str::contains($apiBootstrap, $needle)) {
                    $issues[] = "Route file not required in routes/api.php: {$needle}";
                }
            }
        }

        return $issues;
    }

    /**
     * @return array<int, string>
     */
    protected function auditLayerCompleteness(): array
    {
        $issues = [];
        $requiredLayers = (array) config('architecture.required_layers', []);
        $domainConfig = (array) config('architecture.domains', []);
        $excludedDomains = (array) config('architecture.excluded_domains', []);
        $emptyLayerExceptions = (array) config('architecture.empty_layer_exceptions', []);

        foreach (array_keys($domainConfig) as $domain) {
            if (in_array($domain, $excludedDomains, true)) {
                continue;
            }

            $basePath = app_path('Domains'.DIRECTORY_SEPARATOR.$domain);

            foreach ($requiredLayers as $layer) {
                $layerPath = $basePath.DIRECTORY_SEPARATOR.$layer;

                if (! File::isDirectory($layerPath)) {
                    $issues[] = "Missing layer directory: app/Domains/{$domain}/{$layer}";
                    continue;
                }

                $allowedEmpty = in_array(
                    $layer,
                    (array) Arr::get($emptyLayerExceptions, $domain, []),
                    true
                );

                if ($allowedEmpty) {
                    continue;
                }

                $realFiles = collect(File::files($layerPath))
                    ->reject(fn (SplFileInfo $file): bool => $file->getFilename() === '.gitkeep');

                if ($realFiles->isEmpty()) {
                    $issues[] = "Empty layer directory: app/Domains/{$domain}/{$layer}";
                }
            }
        }

        return $issues;
    }

    /**
     * @return array<int, string>
     */
    protected function auditTenantTraitCoverage(): array
    {
        $issues = [];
        $domainConfig = (array) config('architecture.domains', []);
        $excludedDomains = (array) config('architecture.excluded_domains', []);
        $exceptions = (array) config('architecture.tenant_trait_exceptions', []);

        $models = collect($domainConfig)
            ->keys()
            ->reject(fn (string $domain): bool => in_array($domain, $excludedDomains, true))
            ->flatMap(function (string $domain): array {
                $path = app_path('Domains'.DIRECTORY_SEPARATOR.$domain.DIRECTORY_SEPARATOR.'Models');

                if (! File::isDirectory($path)) {
                    return [];
                }

                return File::files($path);
            });

        foreach ($models as $modelFile) {
            if ($modelFile->getFilename() === '.gitkeep') {
                continue;
            }

            $relative = Str::replaceFirst(app_path().DIRECTORY_SEPARATOR, '', $modelFile->getPathname());
            $class = 'App\\'.str_replace([DIRECTORY_SEPARATOR, '.php'], ['\\', ''], $relative);

            if (in_array($class, $exceptions, true)) {
                continue;
            }

            $content = File::get($modelFile->getPathname());
            if (! Str::contains($content, 'BelongsToCompany')) {
                $issues[] = "Model missing BelongsToCompany trait: {$class}";
            }
        }

        return $issues;
    }

    /**
     * @return array<int, string>
     */
    protected function auditPermissionParity(): array
    {
        $issues = [];
        $seededPermissions = $this->seededPermissions();
        $policyPermissions = $this->extractPolicyPermissions();
        $routePermissions = $this->extractRoutePermissions();

        foreach (array_unique(array_merge($policyPermissions, $routePermissions)) as $permission) {
            if (! in_array($permission, $seededPermissions, true)) {
                $issues[] = "Permission missing in PermissionSeeder: {$permission}";
            }
        }

        return $issues;
    }

    /**
     * @return array<int, string>
     */
    protected function auditUnmappedRouteFiles(): array
    {
        $warnings = [];
        $mapped = collect((array) config('architecture.domains', []))
            ->flatMap(fn (array $metadata): array => (array) Arr::get($metadata, 'api_routes', []))
            ->unique()
            ->values()
            ->all();

        $real = collect(File::files(base_path('routes/api/v1')))
            ->map(fn (SplFileInfo $file): string => $file->getFilename())
            ->sort()
            ->values()
            ->all();

        foreach ($real as $routeFile) {
            if (! in_array($routeFile, $mapped, true)) {
                $warnings[] = "API route file is not mapped in config/architecture.php: routes/api/v1/{$routeFile}";
            }
        }

        return $warnings;
    }

    /**
     * @return array<int, string>
     */
    protected function seededPermissions(): array
    {
        $content = File::get(database_path('seeders/PermissionSeeder.php'));

        preg_match_all("/'([a-z0-9_.-]+)'/", $content, $matches);

        return collect($matches[1])
            ->filter(fn (string $candidate): bool => Str::contains($candidate, '.'))
            ->values()
            ->all();
    }

    /**
     * @return array<int, string>
     */
    protected function extractPolicyPermissions(): array
    {
        $permissions = [];

        $policyFiles = collect(File::allFiles(app_path('Domains')))
            ->filter(fn (SplFileInfo $file): bool => Str::endsWith($file->getFilename(), 'Policy.php'));

        foreach ($policyFiles as $policyFile) {
            preg_match_all(
                "/can\\(\\s*'([a-z0-9_.-]+)'\\s*\\)/",
                File::get($policyFile->getPathname()),
                $matches
            );

            $permissions = array_merge($permissions, $matches[1]);
        }

        return array_values(array_unique($permissions));
    }

    /**
     * @return array<int, string>
     */
    protected function extractRoutePermissions(): array
    {
        $permissions = [];

        foreach (File::files(base_path('routes/api/v1')) as $routeFile) {
            preg_match_all(
                "/permission:([a-z0-9_.-]+)/",
                File::get($routeFile->getPathname()),
                $matches
            );

            $permissions = array_merge($permissions, $matches[1]);
        }

        return array_values(array_unique($permissions));
    }
}
