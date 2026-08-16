<?php

namespace App\Domain\Importacao;

final class ImportacaoSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $tipoImportacao = '',
        public readonly string $status = '',
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
