<?php

namespace App\Application\Atendimento;

use App\Domain\Atendimento\AtendimentoRepositoryInterface;
use App\Domain\Atendimento\AtendimentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

final class ListAtendimentos
{
    public function __construct(
        private readonly AtendimentoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Atendimento\Atendimento>
     */
    public function execute(AtendimentoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        return $this->repository->search($criteria, $tenant);
    }
}
