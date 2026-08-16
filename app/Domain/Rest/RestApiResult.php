<?php

namespace App\Domain\Rest;

/**
 * Resultado JSON da API REST Proativa.
 */
final class RestApiResult
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly array $payload,
    ) {}

    public static function failed(): self
    {
        return new self([
            'response' => [
                'status' => 'failed',
                'message' => 'Failed to process request',
            ],
        ]);
    }

    /**
     * @param  array<string, mixed>  $payload
     */
    public static function success(array $payload): self
    {
        return new self($payload);
    }
}
