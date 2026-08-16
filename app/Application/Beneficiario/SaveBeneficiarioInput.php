<?php

namespace App\Application\Beneficiario;

final class SaveBeneficiarioInput
{
    /**
     * @param  array<string, mixed>  $attributes  Campos já validados (FormRequest)
     */
    public function __construct(
        public readonly array $attributes,
        public readonly int $clienteId,
        public readonly ?int $existingId,
        public readonly ?int $userId,
        public readonly \DateTimeImmutable $now,
    ) {}
}
