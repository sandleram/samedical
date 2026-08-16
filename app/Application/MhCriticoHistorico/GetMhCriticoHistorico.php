<?php

namespace App\Application\MhCriticoHistorico;

use App\Domain\MhCriticoHistorico\MhCriticoHistorico;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoRepositoryInterface;

final class GetMhCriticoHistorico
{
    public function __construct(
        private readonly MhCriticoHistoricoRepositoryInterface $repository,
    ) {}

    public function execute(int $mhCriticoId, int $id): ?MhCriticoHistorico
    {
        return $this->repository->findById($mhCriticoId, $id);
    }

    public function criticoExists(int $mhCriticoId): bool
    {
        return $this->repository->criticoExists($mhCriticoId);
    }
}
