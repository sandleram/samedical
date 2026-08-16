<?php

namespace App\Application\Bi;

use App\Domain\Bi\Bi;
use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetBi
{
    public function __construct(
        private readonly BiRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Bi
    {
        return $this->repository->findById($id, $tenant);
    }
}
