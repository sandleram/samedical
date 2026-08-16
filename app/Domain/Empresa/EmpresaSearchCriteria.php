<?php

namespace App\Domain\Empresa;

final class EmpresaSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $razaoSocial = '',
        public readonly string $cnpj = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 10,
        public readonly int $page = 1,
    ) {}
}
