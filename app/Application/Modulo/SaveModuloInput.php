<?php

namespace App\Application\Modulo;

final class SaveModuloInput
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly array $attributes,
        public readonly ?int $existingId,
        public readonly ?int $userId,
        public readonly \DateTimeImmutable $now,
    ) {}
}
