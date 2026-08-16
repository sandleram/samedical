<?php

namespace App\Application\Beneficio;

use App\Domain\Beneficio\Beneficio;
use App\Domain\Beneficio\BeneficioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetBeneficio
{
    public function __construct(
        private readonly BeneficioRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Beneficio
    {
        return $this->repository->findById($id, $tenant);
    }
}
