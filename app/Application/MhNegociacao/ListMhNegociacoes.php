<?php

namespace App\Application\MhNegociacao;

use App\Domain\MhNegociacao\MhNegociacaoRepositoryInterface;
use App\Domain\MhNegociacao\MhNegociacaoSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListMhNegociacoes
{
    public function __construct(
        private readonly MhNegociacaoRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\MhNegociacao\MhNegociacao>
     */
    public function execute(MhNegociacaoSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
