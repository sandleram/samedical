<?php

namespace App\Application\Parametro;

use App\Domain\Parametro\ParametroRepositoryInterface;
use App\Domain\Parametro\ParametroSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListParametros
{
    public function __construct(
        private readonly ParametroRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Parametro\Parametro>
     */
    public function execute(ParametroSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
