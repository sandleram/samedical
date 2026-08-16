<?php

namespace App\Application\MhCriticoHistorico;

use App\Domain\MhCriticoHistorico\MhCriticoHistoricoRepositoryInterface;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListMhCriticoHistoricos
{
    public function __construct(
        private readonly MhCriticoHistoricoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{critico: ?\App\Domain\MhCritico\MhCritico, rows: PagedResult<\App\Domain\MhCriticoHistorico\MhCriticoHistorico>}
     */
    public function execute(MhCriticoHistoricoSearchCriteria $criteria): array
    {
        return [
            'critico' => $this->repository->findCritico($criteria->mhCriticoId),
            'rows' => $this->repository->search($criteria),
        ];
    }
}
