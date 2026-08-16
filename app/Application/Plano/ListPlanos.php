<?php

namespace App\Application\Plano;

use App\Domain\Plano\PlanoRepositoryInterface;
use App\Domain\Plano\PlanoSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListPlanos
{
    public function __construct(
        private readonly PlanoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Plano\Plano>
     */
    public function execute(PlanoSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
