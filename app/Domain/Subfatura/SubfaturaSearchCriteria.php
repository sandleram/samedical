<?php

namespace App\Domain\Subfatura;

final class SubfaturaSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $descricao = '',
        public readonly string $codigo = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
