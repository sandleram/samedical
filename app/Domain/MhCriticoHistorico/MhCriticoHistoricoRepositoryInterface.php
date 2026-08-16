<?php

namespace App\Domain\MhCriticoHistorico;

use App\Domain\MhCritico\MhCritico;
use App\Domain\Shared\PagedResult;

interface MhCriticoHistoricoRepositoryInterface
{
    /**
     * @return PagedResult<MhCriticoHistorico>
     */
    public function search(MhCriticoHistoricoSearchCriteria $criteria): PagedResult;

    public function findById(int $mhCriticoId, int $id): ?MhCriticoHistorico;

    public function findCritico(int $mhCriticoId): ?MhCritico;

    public function criticoExists(int $mhCriticoId): bool;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): MhCriticoHistorico;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $mhCriticoId, int $id, array $data): MhCriticoHistorico;
}
