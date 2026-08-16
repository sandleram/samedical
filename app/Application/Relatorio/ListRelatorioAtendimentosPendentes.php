<?php

namespace App\Application\Relatorio;

use App\Domain\Relatorio\RelatorioAtendimentoPendenteSearchCriteria;
use App\Domain\Relatorio\RelatorioRepositoryInterface;
use App\Domain\Shared\PagedResult;

final class ListRelatorioAtendimentosPendentes
{
    public function __construct(
        private readonly RelatorioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\Relatorio\RelatorioAtendimentoPendenteRow>
     */
    public function execute(RelatorioAtendimentoPendenteSearchCriteria $criteria): PagedResult
    {
        return $this->repository->searchAtendimentosPendentes($criteria);
    }
}
