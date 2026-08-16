<?php

namespace App\Application\Afastado;

use App\Domain\Afastado\Afastado;
use App\Domain\Afastado\AfastadoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetAfastado
{
    public function __construct(
        private readonly AfastadoRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Afastado
    {
        return $this->repository->findById($id, $tenant);
    }
}
