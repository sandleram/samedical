<?php

namespace App\Application\Beneficio;

use App\Domain\Beneficio\Beneficio;
use App\Domain\Beneficio\BeneficioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareBeneficioForm
{
    public function __construct(
        private readonly BeneficioRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?Beneficio, options: array<string, mixed>}
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
