<?php

namespace App\Domain\Beneficiario;

final class BeneficiarioSearchCriteria
{
    public function __construct(
        public readonly string $id = '',
        public readonly string $nome = '',
        public readonly string $nomeSocial = '',
        public readonly string $cpf = '',
        public readonly string $situacao = '',
        public readonly string $status = '',
        public readonly bool $onlyActiveForNonRoot = false,
        public readonly int $perPage = 15,
        public readonly int $page = 1,
    ) {}
}
