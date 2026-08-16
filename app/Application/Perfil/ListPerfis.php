<?php

namespace App\Application\Perfil;

use App\Domain\Perfil\PerfilRepositoryInterface;
use App\Domain\Perfil\PerfilSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListPerfis
{
    public function __construct(
        private readonly PerfilRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Perfil\Perfil>
     */
    public function execute(PerfilSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
