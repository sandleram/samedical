<?php

namespace App\Application\Subfatura;

use App\Domain\Subfatura\SubfaturaRepositoryInterface;
use App\Domain\Subfatura\SubfaturaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListSubfaturas
{
    public function __construct(
        private readonly SubfaturaRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Subfatura\Subfatura>
     */
    public function execute(SubfaturaSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
