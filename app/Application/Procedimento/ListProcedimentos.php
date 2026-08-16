<?php

namespace App\Application\Procedimento;

use App\Domain\Procedimento\ProcedimentoRepositoryInterface;
use App\Domain\Procedimento\ProcedimentoSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListProcedimentos
{
    public function __construct(
        private readonly ProcedimentoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Procedimento\Procedimento>
     */
    public function execute(ProcedimentoSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
