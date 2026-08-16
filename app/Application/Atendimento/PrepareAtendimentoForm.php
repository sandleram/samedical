<?php

namespace App\Application\Atendimento;

use App\Domain\Atendimento\Atendimento;
use App\Domain\Atendimento\AtendimentoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareAtendimentoForm
{
    public function __construct(
        private readonly AtendimentoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?Atendimento, options: array<string, mixed>}
     */
    public function execute(TenantScope $tenant, ?int $id = null): array
    {
        $row = null;
        if ($id !== null) {
            $row = $this->repository->findById($id, $tenant);
        }

        return [
            'row' => $row,
            'options' => $this->repository->formOptions($tenant),
        ];
    }
}
