<?php

namespace App\Domain\Empresa;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface EmpresaRepositoryInterface
{
    /**
     * @return array<int, string> id => label
     */
    public function optionsForCliente(int $clienteId): array;

    public function belongsToCliente(int $empresaId, int $clienteId): bool;

    /**
     * @return PagedResult<Empresa>
     */
    public function search(EmpresaSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Empresa;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Empresa;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Empresa;
}
