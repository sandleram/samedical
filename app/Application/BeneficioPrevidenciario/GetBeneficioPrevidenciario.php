<?php

namespace App\Application\BeneficioPrevidenciario;

use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciario;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetBeneficioPrevidenciario
{
    public function __construct(
        private readonly BeneficioPrevidenciarioRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?BeneficioPrevidenciario
    {
        return $this->repository->findById($id, $tenant);
    }
}
