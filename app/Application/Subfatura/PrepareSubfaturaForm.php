<?php

namespace App\Application\Subfatura;

use App\Domain\Subfatura\Subfatura;
use App\Domain\Subfatura\SubfaturaRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareSubfaturaForm
{
    public function __construct(
        private readonly SubfaturaRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?Subfatura, options: array<string, mixed>}
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
