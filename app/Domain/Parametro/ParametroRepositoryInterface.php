<?php

namespace App\Domain\Parametro;

use App\Domain\Shared\PagedResult;

interface ParametroRepositoryInterface
{
    /**
     * @return PagedResult<Parametro>
     */
    public function search(ParametroSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?Parametro;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Parametro;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Parametro;

    /**
     * Tipos distintos já cadastrados (id => label, ambos o próprio tipo).
     *
     * @return array<string, string>
     */
    public function distinctTipos(): array;
}
