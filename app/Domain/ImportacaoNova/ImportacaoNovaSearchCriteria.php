<?php

namespace App\Domain\ImportacaoNova;

final class ImportacaoNovaSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $tipoImportacao = '',
        public readonly string $status = '',
        public readonly string $statusProcesso = '',
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
