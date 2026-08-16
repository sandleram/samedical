<?php

namespace App\Application\Usuario;

use App\Domain\Shared\TenantScope;
use App\Domain\Usuario\Usuario;
use App\Domain\Usuario\UsuarioRepositoryInterface;

final class GetUsuario
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant, bool $isRoot): ?Usuario
    {
        return $this->repository->findById($id, $tenant, $isRoot);
    }
}
