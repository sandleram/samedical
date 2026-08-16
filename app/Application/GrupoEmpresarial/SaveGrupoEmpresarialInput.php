<?php

namespace App\Application\GrupoEmpresarial;

final class SaveGrupoEmpresarialInput
{
    /**
     * @param  array<string, mixed>  $attributes  Campos já validados (FormRequest)
     */
    public function __construct(
        public readonly array $attributes,
        public readonly ?int $existingId,
        public readonly \DateTimeImmutable $now,
    ) {}
}
