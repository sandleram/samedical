<?php

namespace App\Domain\Perfil;

use App\Domain\Shared\PagedResult;

interface PerfilRepositoryInterface
{
    /**
     * @return PagedResult<Perfil>
     */
    public function search(PerfilSearchCriteria $criteria): PagedResult;

    public function findById(int $id): ?Perfil;

    /**
     * Persist perfil + sync perfil_modulo (transaction).
     *
     * @param  array<string, mixed>  $data
     * @param  array<int, array{id?: int|string|null, permissao?: int|string|null}>  $perfilModulos  keyed by modulo_id
     */
    public function save(array $data, array $perfilModulos, ?int $existingId): Perfil;

    /**
     * @return array<int|string, string> id => nome (sem placeholder)
     */
    public function optionsActive(bool $includeRootPerfil): array;
}
