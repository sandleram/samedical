<?php

namespace App\Application\GrupoEmpresarial;

use App\Domain\GrupoEmpresarial\GrupoEmpresarialRepositoryInterface;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListGrupoEmpresariais
{
    public function __construct(
        private readonly GrupoEmpresarialRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\GrupoEmpresarial\GrupoEmpresarial>
     */
    public function execute(GrupoEmpresarialSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
