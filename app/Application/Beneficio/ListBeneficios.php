<?php

namespace App\Application\Beneficio;

use App\Domain\Beneficio\BeneficioRepositoryInterface;
use App\Domain\Beneficio\BeneficioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListBeneficios
{
    public function __construct(
        private readonly BeneficioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Beneficio\Beneficio>
     */
    public function execute(BeneficioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
