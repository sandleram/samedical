<?php

namespace App\Application\ImportacaoNova;

use App\Domain\ImportacaoNova\ImportacaoNovaRepositoryInterface;
use App\Domain\ImportacaoNova\ImportacaoNovaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListImportacaoNovas
{
    public function __construct(
        private readonly ImportacaoNovaRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\ImportacaoNova\ImportacaoNova>
     */
    public function execute(ImportacaoNovaSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
