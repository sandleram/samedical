<?php

namespace App\Application\BeneficioPrevidenciario;

use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioRepositoryInterface;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListBeneficioPrevidenciarios
{
    public function __construct(
        private readonly BeneficioPrevidenciarioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciario>
     */
    public function execute(BeneficioPrevidenciarioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
