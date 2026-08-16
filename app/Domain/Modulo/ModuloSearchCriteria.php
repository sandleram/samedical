<?php

namespace App\Domain\Modulo;

final class ModuloSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $moduloId = '',
        public readonly string $nome = '',
        public readonly string $controller = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
