<?php

namespace App\Application\Perfil;

final class SavePerfilInput
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
