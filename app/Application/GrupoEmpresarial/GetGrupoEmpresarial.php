<?php

namespace App\Application\GrupoEmpresarial;

use App\Domain\GrupoEmpresarial\GrupoEmpresarial;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetGrupoEmpresarial
{
    public function __construct(
        private readonly GrupoEmpresarialRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?GrupoEmpresarial
    {
        return $this->repository->findById($id, $tenant);
    }
}
