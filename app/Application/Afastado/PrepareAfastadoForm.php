<?php

namespace App\Application\Afastado;

use App\Domain\Afastado\Afastado;
use App\Domain\Afastado\AfastadoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareAfastadoForm
{
    public function __construct(
        private readonly AfastadoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?Afastado, options: array<string, mixed>}
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
