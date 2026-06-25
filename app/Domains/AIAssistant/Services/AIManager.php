<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Services;

use RuntimeException;

use App\Domains\AIAssistant\Providers\GroqProvider;
use App\Domains\AIAssistant\Providers\Contracts\AIProviderInterface;

class AIManager
{
    protected AIProviderInterface $provider;

    public function __construct()
    {
        $this->provider = $this->resolveProvider();
    }

    protected function resolveProvider(): AIProviderInterface
    {
        $driver = config(
            'ai.provider',
            'groq'
        );

        return match ($driver) {

            'groq' =>
                app(GroqProvider::class),

            default =>
                throw new RuntimeException(
                    "Provider [$driver] no soportado."
                )
        };
    }

    public function provider(): AIProviderInterface
    {
        return $this->provider;
    }

    public function chat(
        array $messages,
        array $options = []
    ): array {

        return $this->provider
            ->chat(
                $messages,
                $options
            );
    }

    public function healthCheck(): bool
    {
        return $this->provider
            ->healthCheck();
    }
}
