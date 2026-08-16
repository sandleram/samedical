<?php

namespace App\Application\Empresa;

use App\Domain\Empresa\Empresa;
use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetEmpresa
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Empresa
    {
        return $this->repository->findById($id, $tenant);
    }
}
