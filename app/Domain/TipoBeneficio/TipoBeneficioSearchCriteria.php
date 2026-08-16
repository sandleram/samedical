<?php

namespace App\Domain\TipoBeneficio;

final class TipoBeneficioSearchCriteria
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
