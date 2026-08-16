<?php

namespace App\Application\BeneficioPrevidenciario;

use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciario;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareBeneficioPrevidenciarioForm
{
    public function __construct(
        private readonly BeneficioPrevidenciarioRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?BeneficioPrevidenciario, options: array<string, mixed>}
     */
    public function execute(int $clienteId, TenantScope $tenant, ?int $id = null): array
    {
        $row = null;
        if ($id !== null) {
            $row = $this->repository->findById($id, $tenant);
        }

        return [
            'row' => $row,
            'options' => $this->repository->formOptions($tenant, $clienteId),
        ];
    }
}
