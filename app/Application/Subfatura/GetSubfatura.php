<?php

namespace App\Application\Subfatura;

use App\Domain\Subfatura\Subfatura;
use App\Domain\Subfatura\SubfaturaRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetSubfatura
{
    public function __construct(
        private readonly SubfaturaRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Subfatura
    {
        return $this->repository->findById($id, $tenant);
    }
}
