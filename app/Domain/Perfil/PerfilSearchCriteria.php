<?php

namespace App\Domain\Perfil;

final class PerfilSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 10,
        public readonly int $page = 1,
    ) {}
}
