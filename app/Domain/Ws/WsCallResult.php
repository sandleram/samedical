<?php

namespace App\Domain\Ws;

/**
 * Resultado do WS call_bi_*.
 */
final class WsCallResult
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public readonly int $httpStatus,
        public readonly array $payload,
    ) {}

    public static function forbidden(): self
    {
        return new self(403, [
            'status' => 'failed',
            'message' => 'Failed to process request',
        ]);
    }

    /**
     * @param  list<array<string, mixed>>  $data
     */
    public static function success(string $endpoint, array $data): self
    {
        return new self(200, [
            'status' => 'success',
            'endpoint' => $endpoint,
            'count' => count($data),
            'data' => $data,
        ]);
    }
}
