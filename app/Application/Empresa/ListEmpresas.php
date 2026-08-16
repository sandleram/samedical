<?php

namespace App\Application\Empresa;

use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\Empresa\EmpresaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListEmpresas
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Empresa\Empresa>
     */
    public function execute(EmpresaSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
