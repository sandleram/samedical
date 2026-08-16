<?php

namespace App\Application\Cliente;

use App\Domain\Cliente\ClienteRepositoryInterface;
use App\Domain\Cliente\ClienteSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListClientes
{
    public function __construct(
        private readonly ClienteRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Cliente\Cliente>
     */
    public function execute(ClienteSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
