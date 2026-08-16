<?php

namespace App\Application\Usuario;

final class SaveUsuarioInput
{
    /**
     * @param  array<string, mixed>  $attributes
     */
    public function __construct(
        public readonly array $attributes,
        public readonly ?int $existingId,
        public readonly ?int $userId,
        public readonly ?int $grupoEmpresarialId,
        public readonly bool $isRoot,
        public readonly \DateTimeImmutable $now,
    ) {}
}
