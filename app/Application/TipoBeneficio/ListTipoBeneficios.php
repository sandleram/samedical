<?php

namespace App\Application\TipoBeneficio;

use App\Domain\TipoBeneficio\TipoBeneficioRepositoryInterface;
use App\Domain\TipoBeneficio\TipoBeneficioSearchCriteria;
use App\Domain\Shared\PagedResult;

final class ListTipoBeneficios
{
    public function __construct(
        private readonly TipoBeneficioRepositoryInterface $repository,
    ) {}

    /**
     * @return PagedResult<\App\Domain\TipoBeneficio\TipoBeneficio>
     */
    public function execute(TipoBeneficioSearchCriteria $criteria): PagedResult
    {
        return $this->repository->search($criteria);
    }
}
