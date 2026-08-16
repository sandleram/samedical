<?php

namespace App\Application\Modulo;

use App\Domain\Modulo\Modulo;
use App\Domain\Modulo\ModuloRepositoryInterface;

final class GetModulo
{
    public function __construct(
        private readonly ModuloRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Modulo
    {
        return $this->repository->findById($id);
    }
}
