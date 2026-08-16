<?php

namespace App\Application\MhPrestador;

use App\Domain\MhPrestador\MhPrestadorRepositoryInterface;
use App\Domain\MhPrestador\MhPrestadorSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListMhPrestadores
{
    public function __construct(
        private readonly MhPrestadorRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\MhPrestador\MhPrestador>
     */
    public function execute(MhPrestadorSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
