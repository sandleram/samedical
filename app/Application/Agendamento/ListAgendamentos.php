<?php

namespace App\Application\Agendamento;

use App\Domain\Agendamento\AgendamentoRepositoryInterface;
use App\Domain\Agendamento\AgendamentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListAgendamentos
{
    public function __construct(
        private readonly AgendamentoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Agendamento\Agendamento>
     */
    public function execute(AgendamentoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
