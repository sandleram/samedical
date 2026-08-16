<?php

namespace App\Application\Plano;

use App\Domain\Plano\Plano;
use App\Domain\Plano\PlanoRepositoryInterface;

final class GetPlano
{
    public function __construct(
        private readonly PlanoRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Plano
    {
        return $this->repository->findById($id);
    }
}
