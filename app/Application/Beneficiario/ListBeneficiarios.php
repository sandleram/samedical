<?php

namespace App\Application\Beneficiario;

use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Beneficiario\BeneficiarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListBeneficiarios
{
    public function __construct(
        private readonly BeneficiarioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Beneficiario\Beneficiario>
     */
    public function execute(BeneficiarioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
