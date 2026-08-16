<?php

namespace App\Domain\Relatorio;

final class RelatorioAfastadoSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $cpf = '',
        public readonly int $perPage = 30,
        public readonly int $page = 1,
    ) {}
}
