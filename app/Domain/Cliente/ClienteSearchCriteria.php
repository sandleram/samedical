<?php

namespace App\Domain\Cliente;

final class ClienteSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
