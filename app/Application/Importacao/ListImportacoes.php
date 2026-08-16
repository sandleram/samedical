<?php

namespace App\Application\Importacao;

use App\Domain\Importacao\ImportacaoRepositoryInterface;
use App\Domain\Importacao\ImportacaoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListImportacoes
{
    public function __construct(
        private readonly ImportacaoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Importacao\Importacao>
     */
    public function execute(ImportacaoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
