<?php

namespace App\Domain\TipoBeneficio;

use App\Domain\Shared\PagedResult;

interface TipoBeneficioRepositoryInterface
{
    /**
     * @return PagedResult<TipoBeneficio>
     */
    public function search(TipoBeneficioSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?TipoBeneficio;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): TipoBeneficio;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): TipoBeneficio;

    /**
     * @return array<int|string, string>
     */
    public function optionsActive(): array;

}
