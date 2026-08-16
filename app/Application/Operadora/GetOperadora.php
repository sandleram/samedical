<?php

namespace App\Application\Operadora;

use App\Domain\Operadora\Operadora;
use App\Domain\Operadora\OperadoraRepositoryInterface;

final class GetOperadora
{
    public function __construct(
        private readonly OperadoraRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Operadora
    {
        return $this->repository->findById($id);
    }
}
