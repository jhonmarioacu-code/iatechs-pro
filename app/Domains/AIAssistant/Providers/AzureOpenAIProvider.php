<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Providers;

use RuntimeException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Http;
use App\Domains\AIAssistant\Providers\Contracts\AIProviderInterface;

class AzureOpenAIProvider implements AIProviderInterface
{
    protected string $endpoint;

    protected ?string $apiKey;

    protected string $model;

    public function __construct()
    {
        $this->endpoint = (string) config('services.azure_openai.endpoint', '');
        $this->apiKey = config('services.azure_openai.api_key');
        $this->model = (string) config('services.azure_openai.model', config('ai.model', 'gpt-4.1-mini'));
    }

    public function chat(array $messages, array $options = []): array
    {
        if (!$this->isConfigured()) {
            throw new RuntimeException('Azure OpenAI no esta configurado. Define AZURE_OPENAI_RESPONSES_ENDPOINT y AZURE_OPENAI_API_KEY.');
        }

        $input = [];

        foreach ($messages as $message) {
            $role = (string) Arr::get($message, 'role', 'user');
            $content = (string) Arr::get($message, 'content', '');

            if ($content === '') {
                continue;
            }

            $input[] = [
                'role' => $role,
                'content' => [
                    [
                        'type' => 'input_text',
                        'text' => $content,
                    ],
                ],
            ];
        }

        $payload = [
            'model' => $options['model'] ?? $this->model,
            'input' => $input,
        ];

        if (isset($options['temperature'])) {
            $payload['temperature'] = (float) $options['temperature'];
        }

        if (isset($options['max_output_tokens'])) {
            $payload['max_output_tokens'] = (int) $options['max_output_tokens'];
        }

        $response = Http::withToken($this->apiKey)->post($this->endpoint, $payload);

        if (!$response->successful()) {
            $body = (string) $response->body();
            throw new RuntimeException('Azure OpenAI API error: '.mb_substr($body, 0, 500));
        }

        $json = $response->json();
        $text = $this->extractText($json);

        return [
            'raw' => $json,
            'choices' => [
                [
                    'message' => [
                        'content' => $text !== '' ? $text : 'Sin respuesta',
                    ],
                ],
            ],
        ];
    }

    public function healthCheck(): bool
    {
        if (!$this->isConfigured()) {
            return false;
        }

        try {
            $response = Http::withToken($this->apiKey)->post($this->endpoint, [
                'model' => $this->model,
                'input' => [
                    [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'input_text',
                                'text' => 'ping',
                            ],
                        ],
                    ],
                ],
                'max_output_tokens' => 5,
            ]);

            return $response->successful();
        } catch (\Throwable) {
            return false;
        }
    }

    public function getDriver(): string
    {
        return 'azure_openai';
    }

    public function getModel(): string
    {
        return $this->model;
    }

    protected function isConfigured(): bool
    {
        return $this->endpoint !== '' && !empty($this->apiKey);
    }

    protected function extractText(array $response): string
    {
        $outputText = Arr::get($response, 'output_text');
        if (is_string($outputText) && trim($outputText) !== '') {
            return trim($outputText);
        }

        $output = Arr::get($response, 'output', []);

        if (is_array($output)) {
            $chunks = [];

            foreach ($output as $item) {
                $content = Arr::get($item, 'content', []);

                if (!is_array($content)) {
                    continue;
                }

                foreach ($content as $contentItem) {
                    $type = (string) Arr::get($contentItem, 'type', '');
                    $text = Arr::get($contentItem, 'text', Arr::get($contentItem, 'output_text'));

                    if ($type === 'output_text' && is_string($text) && trim($text) !== '') {
                        $chunks[] = trim($text);
                    }
                }
            }

            if ($chunks !== []) {
                return implode("\n", $chunks);
            }
        }

        $legacy = Arr::get($response, 'choices.0.message.content');
        if (is_string($legacy)) {
            return $legacy;
        }

        return '';
    }
}
