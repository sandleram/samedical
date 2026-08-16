<?php

namespace App\Application\Beneficiario;

use App\Domain\Beneficiario\Beneficiario;
use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetBeneficiario
{
    public function __construct(
        private readonly BeneficiarioRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Beneficiario
    {
        return $this->repository->findById($id, $tenant);
    }
}
