<?php

namespace App\Application\Cliente;

final class SaveClienteInput
{
    /**
     * @param  array<string, mixed>  $attributes  Campos já validados (FormRequest)
     */
    public function __construct(
        public readonly array $attributes,
        public readonly int $grupoEmpresarialId,
        public readonly ?int $existingId,
        public readonly ?int $userId,
        public readonly \DateTimeImmutable $now,
    ) {}
}
