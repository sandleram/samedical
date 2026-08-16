<?php

namespace App\Domain\Atendimento;

final class AtendimentoSearchCriteria
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
