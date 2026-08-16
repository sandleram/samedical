<?php

namespace App\Application\Cliente;

use App\Domain\Cliente\Cliente;
use App\Domain\Cliente\ClienteRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetCliente
{
    public function __construct(
        private readonly ClienteRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Cliente
    {
        return $this->repository->findById($id, $tenant);
    }
}
