<?php

namespace App\Application\Absenteismo;

use App\Domain\Absenteismo\Absenteismo;
use App\Domain\Absenteismo\AbsenteismoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareAbsenteismoForm
{
    public function __construct(
        private readonly AbsenteismoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?Absenteismo, options: array<string, mixed>}
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
