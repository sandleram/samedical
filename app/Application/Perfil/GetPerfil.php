<?php

namespace App\Application\Perfil;

use App\Domain\Perfil\Perfil;
use App\Domain\Perfil\PerfilRepositoryInterface;

final class GetPerfil
{
    public function __construct(
        private readonly PerfilRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Perfil
    {
        return $this->repository->findById($id);
    }
}
