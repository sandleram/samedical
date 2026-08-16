<?php

namespace App\Domain\Parametro;

final class ParametroSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $tipo = '',
        public readonly string $valor = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
