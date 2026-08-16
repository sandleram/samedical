<?php

namespace App\Application\Agendamento;

use App\Domain\Agendamento\Agendamento;
use App\Domain\Agendamento\AgendamentoRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class GetAgendamento
{
    public function __construct(
        private readonly AgendamentoRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant): ?Agendamento
    {
        return $this->repository->findById($id, $tenant);
    }
}
