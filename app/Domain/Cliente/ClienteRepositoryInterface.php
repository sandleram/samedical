<?php

namespace App\Domain\Cliente;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface ClienteRepositoryInterface
{
    /**
     * @return PagedResult<Cliente>
     */
    public function search(ClienteSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Cliente;

    /**
     * Busca sem filtro de tenant (ação selecione).
     */
    public function findForSelecao(int $id): ?Cliente;

    /**
     * Lista clientes disponíveis para seleção de contexto.
     *
     * @return list<Cliente>
     */
    public function listForSelecao(int $usuarioId, int $perfilId, bool $isRoot): array;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Cliente;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Cliente;
}
