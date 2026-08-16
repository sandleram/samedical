<?php

namespace App\Application\Relatorio;

use App\Domain\Relatorio\RelatorioAfastadoSearchCriteria;
use App\Domain\Relatorio\RelatorioRepositoryInterface;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListRelatorioAfastados
{
    public function __construct(
        private readonly RelatorioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Relatorio\RelatorioAfastadoRow>
     */
    public function execute(RelatorioAfastadoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->searchAfastados($criteria, $tenant);
    }
}
