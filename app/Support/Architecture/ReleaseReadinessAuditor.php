<?php

declare(strict_types=1);

namespace App\Support\Architecture;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ReleaseReadinessAuditor
{
    public function run(): array
    {
        $errors = [];
        $warnings = [];
        $envExample = $this->loadEnvExample();

        $errors = array_merge($errors, $this->auditRequiredFiles());
        $errors = array_merge($errors, $this->auditComposerScripts());
        $errors = array_merge($errors, $this->auditRequiredEnvKeys($envExample));
        $errors = array_merge($errors, $this->auditRequiredEnvValues($envExample));

        $integrationReport = $this->auditIntegrationEnvMatrix($envExample);
        $errors = array_merge($errors, $integrationReport['errors']);
        $warnings = array_merge($warnings, $integrationReport['warnings']);

        return [
            'errors' => array_values(array_unique($errors)),
            'warnings' => array_values(array_unique($warnings)),
            'stats' => [
                'required_files' => count((array) config('release_gate.required_files', [])),
                'required_env_keys' => count((array) config('release_gate.required_env_keys', [])),
                'env_example_keys' => count($envExample),
                'integration_checks' => count((array) config('release_gate.integration_env_matrix', [])),
            ],
        ];
    }

    /**
     * @return array<string, string>
     */
    protected function loadEnvExample(): array
    {
        $path = base_path('.env.example');
        if (! File::exists($path)) {
            return [];
        }

        $map = [];
        $lines = preg_split('/\R/', File::get($path)) ?: [];

        foreach ($lines as $line) {
            $trimmed = trim((string) $line);
            if ($trimmed === '' || Str::startsWith($trimmed, '#') || ! Str::contains($trimmed, '=')) {
                continue;
            }

            [$key, $value] = array_pad(explode('=', $trimmed, 2), 2, '');
            $map[trim($key)] = trim($value);
        }

        return $map;
    }

    /**
     * @return array<int, string>
     */
    protected function auditRequiredFiles(): array
    {
        $issues = [];

        foreach ((array) config('release_gate.required_files', []) as $requiredFile) {
            if (! File::exists(base_path((string) $requiredFile))) {
                $issues[] = "Missing required release file: {$requiredFile}";
            }
        }

        return $issues;
    }

    /**
     * @return array<int, string>
     */
    protected function auditComposerScripts(): array
    {
        $issues = [];
        $composerPath = base_path('composer.json');

        if (! File::exists($composerPath)) {
            return ['Missing required release file: composer.json'];
        }

        /** @var array<string, mixed>|null $composer */
        $composer = json_decode(File::get($composerPath), true);
        if (! is_array($composer)) {
            return ['Unable to parse composer.json'];
        }

        $scripts = (array) Arr::get($composer, 'scripts', []);

        foreach ((array) config('release_gate.required_composer_scripts', []) as $script) {
            if (! array_key_exists((string) $script, $scripts)) {
                $issues[] = "Missing composer script for release gate: {$script}";
            }
        }

        return $issues;
    }

    /**
     * @param array<string, string> $envExample
     * @return array<int, string>
     */
    protected function auditRequiredEnvKeys(array $envExample): array
    {
        $issues = [];

        foreach ((array) config('release_gate.required_env_keys', []) as $key) {
            if (! array_key_exists((string) $key, $envExample)) {
                $issues[] = "Missing .env.example key: {$key}";
            }
        }

        return $issues;
    }

    /**
     * @param array<string, string> $envExample
     * @return array<int, string>
     */
    protected function auditRequiredEnvValues(array $envExample): array
    {
        $issues = [];

        foreach ((array) config('release_gate.required_env_values', []) as $key => $expectedValue) {
            $actualValue = (string) ($envExample[(string) $key] ?? '');
            if ($actualValue !== (string) $expectedValue) {
                $issues[] = "Unexpected .env.example value for {$key}. Expected '{$expectedValue}', found '{$actualValue}'.";
            }
        }

        return $issues;
    }

    /**
     * @param array<string, string> $envExample
     * @return array{errors: array<int, string>, warnings: array<int, string>}
     */
    protected function auditIntegrationEnvMatrix(array $envExample): array
    {
        $errors = [];
        $warnings = [];

        foreach ((array) config('release_gate.integration_env_matrix', []) as $integration => $metadata) {
            $required = (bool) Arr::get($metadata, 'required', false);
            $missingKeys = collect((array) Arr::get($metadata, 'keys', []))
                ->filter(fn (string $key): bool => ! array_key_exists($key, $envExample))
                ->values()
                ->all();

            if ($missingKeys === []) {
                continue;
            }

            $message = 'Integration env keys missing for '.$integration.': '.implode(', ', $missingKeys);
            if ($required) {
                $errors[] = $message;
            } else {
                $warnings[] = $message;
            }
        }

        return [
            'errors' => $errors,
            'warnings' => $warnings,
        ];
    }
}

