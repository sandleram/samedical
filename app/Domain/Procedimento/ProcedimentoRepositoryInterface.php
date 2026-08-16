<?php

namespace App\Domain\Procedimento;

use App\Domain\Shared\PagedResult;

interface ProcedimentoRepositoryInterface
{
    /**
     * @return PagedResult<Procedimento>
     */
    public function search(ProcedimentoSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?Procedimento;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Procedimento;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Procedimento;

}
