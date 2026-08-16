<?php

namespace App\Application\Procedimento;

use App\Domain\Procedimento\Procedimento;
use App\Domain\Procedimento\ProcedimentoRepositoryInterface;

final class GetProcedimento
{
    public function __construct(
        private readonly ProcedimentoRepositoryInterface $repository,
    ) {}

    public function execute(int $id): ?Procedimento
    {
        return $this->repository->findById($id);
    }
}
