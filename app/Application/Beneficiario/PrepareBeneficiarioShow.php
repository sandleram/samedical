<?php

namespace App\Application\Beneficiario;

use App\Domain\Beneficiario\Beneficiario;
use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareBeneficiarioShow
{
    public function __construct(
        private readonly BeneficiarioRepositoryInterface $repository,
    ) {}

    /**
     * @return array{beneficiario: Beneficiario, related: array<string, list<array<string, mixed>>>}|null
     */
    public function execute(int $id, TenantScope $tenant): ?array
    {
        $beneficiario = $this->repository->findById($id, $tenant);
        if (! $beneficiario) {
            return null;
        }

        return [
            'beneficiario' => $beneficiario,
            'related' => $this->repository->relatedForView($id, $tenant),
        ];
    }
}
