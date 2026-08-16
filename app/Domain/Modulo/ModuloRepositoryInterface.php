<?php

namespace App\Domain\Modulo;

use App\Domain\Shared\PagedResult;

interface ModuloRepositoryInterface
{
    /**
     * @return PagedResult<Modulo>
     */
    public function search(ModuloSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?Modulo;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Modulo;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data): Modulo;

    /**
     * @return array<int|string, string>
     */
    public function parentOptions(): array;

    /**
     * Módulos ativos ordenados (formulário/show de Perfil).
     *
     * @return list<Modulo>
     */
    public function listActiveOrdered(): array;
}
