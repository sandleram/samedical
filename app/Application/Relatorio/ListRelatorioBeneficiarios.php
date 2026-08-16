<?php

namespace App\Application\Relatorio;

use App\Domain\Relatorio\RelatorioBeneficiarioSearchCriteria;
use App\Domain\Relatorio\RelatorioRepositoryInterface;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListRelatorioBeneficiarios
{
    public function __construct(
        private readonly RelatorioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Relatorio\RelatorioBeneficiarioRow>
     */
    public function execute(RelatorioBeneficiarioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->searchBeneficiarios($criteria, $tenant);
    }
}
