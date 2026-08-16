<?php

namespace App\Application\Afastado;

use App\Domain\Afastado\AfastadoRepositoryInterface;
use App\Domain\Afastado\AfastadoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListAfastados
{
    public function __construct(
        private readonly AfastadoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Afastado\Afastado>
     */
    public function execute(AfastadoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
