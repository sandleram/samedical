<?php

namespace App\Application\Operadora;

use App\Domain\Operadora\OperadoraRepositoryInterface;
use App\Domain\Operadora\OperadoraSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListOperadoras
{
    public function __construct(
        private readonly OperadoraRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Operadora\Operadora>
     */
    public function execute(OperadoraSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
