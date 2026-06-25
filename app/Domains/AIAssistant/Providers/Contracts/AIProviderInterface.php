<?php

declare(strict_types=1);

namespace App\Domains\AIAssistant\Providers\Contracts;

interface AIProviderInterface
{
    /**
     * Enviar conversación al proveedor IA.
     */
    public function chat(
        array $messages,
        array $options = []
    ): array;

    /**
     * Validar estado del proveedor.
     */
    public function healthCheck(): bool;

    /**
     * Obtener nombre del proveedor.
     */
    public function getDriver(): string;

    /**
     * Obtener modelo utilizado.
     */
    public function getModel(): string;
}