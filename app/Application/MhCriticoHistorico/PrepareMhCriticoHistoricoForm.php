<?php

namespace App\Application\MhCriticoHistorico;

use App\Domain\MhCriticoHistorico\MhCriticoHistorico;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoRepositoryInterface;

final class PrepareMhCriticoHistoricoForm
{
    public function __construct(
        private readonly MhCriticoHistoricoRepositoryInterface $repository,
    ) {}

    /**
     * @return array{criticoExists: bool, row: ?MhCriticoHistorico}
     */
    public function execute(int $mhCriticoId, ?int $id = null): array
    {
        $row = null;
        if ($id !== null) {
            $row = $this->repository->findById($mhCriticoId, $id);
        }

        return [
            'criticoExists' => $this->repository->criticoExists($mhCriticoId),
            'row' => $row,
        ];
    }
}
