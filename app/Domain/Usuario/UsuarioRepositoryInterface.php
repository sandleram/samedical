<?php

namespace App\Domain\Usuario;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface UsuarioRepositoryInterface
{
    /**
     * @return PagedResult<Usuario>
     */
    public function search(UsuarioSearchCriteria $criteria, TenantScope $tenant, bool $isRoot): PagedResult;

    public function findById(int $id, TenantScope $tenant, bool $isRoot): ?Usuario;

    /**
     * Persist usuario + sync usuario_cliente / usuario_bi (transaction).
     * Hash de senha quando `senha` presente no payload.
     *
     * @param  array<string, mixed>  $data
     * @param  list<int>  $clienteIds
     * @param  list<int>  $biIds
     */
    public function save(array $data, array $clienteIds, array $biIds, ?int $existingId, TenantScope $tenant, bool $isRoot): Usuario;

    /**
     * @return array<int, list<array<string, mixed>>>
     */
    public function clienteMatrix(?int $grupoEmpresarialId, bool $isRoot): array;

    /**
     * @return array<int, list<array<string, mixed>>>
     */
    public function biMatrix(): array;

    public function findGrupoEmpresarialIdByCliente(int $clienteId): ?int;
}
