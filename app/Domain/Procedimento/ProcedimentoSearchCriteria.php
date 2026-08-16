<?php

namespace App\Domain\Procedimento;

final class ProcedimentoSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $codProcedimento = '',
        public readonly string $dsProcedimento = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
