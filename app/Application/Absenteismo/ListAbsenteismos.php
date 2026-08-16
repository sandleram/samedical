<?php

namespace App\Application\Absenteismo;

use App\Domain\Absenteismo\AbsenteismoRepositoryInterface;
use App\Domain\Absenteismo\AbsenteismoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListAbsenteismos
{
    public function __construct(
        private readonly AbsenteismoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Absenteismo\Absenteismo>
     */
    public function execute(AbsenteismoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
