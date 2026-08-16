<?php

namespace App\Domain\MhCriticoHistorico;

final class MhCriticoHistoricoSearchCriteria
{
    public function __construct(
        public readonly int $mhCriticoId,
        public readonly string $id = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
