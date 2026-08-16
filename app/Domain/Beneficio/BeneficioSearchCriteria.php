<?php

namespace App\Domain\Beneficio;

final class BeneficioSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $descricao = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
