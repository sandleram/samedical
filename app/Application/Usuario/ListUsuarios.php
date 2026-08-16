<?php

namespace App\Application\Usuario;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Domain\Usuario\UsuarioRepositoryInterface;
use App\Domain\Usuario\UsuarioSearchCriteria;

final class ListUsuarios
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Usuario\Usuario>
     */
    public function execute(UsuarioSearchCriteria $criteria, TenantScope $tenant, bool $isRoot): PagedResult
    {
        return $this->repository->search($criteria, $tenant, $isRoot);
    }
}
