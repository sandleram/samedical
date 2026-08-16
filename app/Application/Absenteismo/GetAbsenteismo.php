<?php

namespace App\Application\Absenteismo;

use App\Domain\Absenteismo\Absenteismo;
use App\Domain\Absenteismo\AbsenteismoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetAbsenteismo
{
    public function __construct(
        private readonly AbsenteismoRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Absenteismo
    {
        return $this->repository->findById($id, $tenant);
    }
}
