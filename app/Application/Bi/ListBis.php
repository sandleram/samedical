<?php

namespace App\Application\Bi;

use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Bi\BiSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListBis
{
    public function __construct(
        private readonly BiRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Bi\Bi>
     */
    public function execute(BiSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
