<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Providers;

use Illuminate\Support\Facades\Http;

use App\Domains\AIAssistant\Providers\Contracts\AIProviderInterface;

class GroqProvider implements AIProviderInterface
{
    protected string $endpoint;

    protected string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->endpoint = config(
            'services.groq.endpoint',
            'https://api.groq.com/openai/v1/chat/completions'
        );

        $this->apiKey = config(
            'services.groq.api_key'
        );

        $this->model = config(
            'services.groq.model',
            'llama-3.3-70b-versatile'
        );
    }

    public function chat(
        array $messages,
        array $options = []
    ): array {

        $response = Http::withToken(
            $this->apiKey
        )->post(
            $this->endpoint,
            [

                'model' => $this->model,

                'messages' => $messages,

                'temperature' =>
                    $options['temperature'] ?? 0.7,

                'max_tokens' =>
                    $options['max_tokens'] ?? 4096,
            ]
        );

        if (!$response->successful()) {

            throw new \RuntimeException(
                'Groq API Error'
            );
        }

        return $response->json();
    }

    public function healthCheck(): bool
    {
        try {

            $response = Http::withToken(
                $this->apiKey
            )->get(
                'https://api.groq.com/openai/v1/models'
            );

            return $response->successful();

        } catch (\Throwable $e) {

            return false;
        }
    }

    public function getDriver(): string
    {
        return 'groq';
    }

    public function getModel(): string
    {
        return $this->model;
    }
}