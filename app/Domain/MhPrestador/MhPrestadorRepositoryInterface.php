<?php

namespace App\Domain\MhPrestador;

use App\Domain\Shared\PagedResult;

interface MhPrestadorRepositoryInterface
{
    /**
     * @return PagedResult<MhPrestador>
     */
    public function search(MhPrestadorSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?MhPrestador;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): MhPrestador;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): MhPrestador;

    /**
     * @return array<int|string, string>
     */
    public function optionsAll(): array;
}
