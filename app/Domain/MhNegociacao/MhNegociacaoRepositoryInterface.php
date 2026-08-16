<?php

namespace App\Domain\MhNegociacao;

use App\Domain\Shared\PagedResult;

interface MhNegociacaoRepositoryInterface
{
    /**
     * @return PagedResult<MhNegociacao>
     */
    public function search(MhNegociacaoSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?MhNegociacao;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): MhNegociacao;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): MhNegociacao;

    /**
     * @return array<int|string, string>
     */
    public function formPrestadorOptions(): array;
}
