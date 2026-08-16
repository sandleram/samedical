<?php

namespace App\Domain\Operadora;

use App\Domain\Shared\PagedResult;

interface OperadoraRepositoryInterface
{
    /**
     * @return PagedResult<Operadora>
     */
    public function search(OperadoraSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?Operadora;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Operadora;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Operadora;

    /**
     * @return array<int|string, string>
     */
    public function options(bool $withPlaceholder = true): array;
}
