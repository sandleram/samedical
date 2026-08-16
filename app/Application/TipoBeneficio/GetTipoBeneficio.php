<?php

namespace App\Application\TipoBeneficio;

use App\Domain\TipoBeneficio\TipoBeneficio;
use App\Domain\TipoBeneficio\TipoBeneficioRepositoryInterface;

final class GetTipoBeneficio
{
    public function __construct(
        private readonly TipoBeneficioRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?TipoBeneficio
    {
        return $this->repository->findById($id);
    }
}
