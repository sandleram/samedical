<?php

namespace App\Application\Modulo;

use App\Domain\Modulo\ModuloRepositoryInterface;
use App\Domain\Modulo\ModuloSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListModulos
{
    public function __construct(
        private readonly ModuloRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Modulo\Modulo>
     */
    public function execute(ModuloSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
