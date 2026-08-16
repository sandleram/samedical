<?php

namespace App\Application\Agendamento;

use App\Domain\Agendamento\Agendamento;
use App\Domain\Agendamento\AgendamentoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareAgendamentoForm
{
    public function __construct(
        private readonly AgendamentoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{row: ?Agendamento, options: array<string, mixed>}
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
