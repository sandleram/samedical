<?php

namespace App\Domain\Afastado;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface AfastadoRepositoryInterface
{
    /**
     * @return PagedResult<Afastado>
     */
    public function search(AfastadoSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Afastado;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Afastado;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Afastado;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant, int $clienteId): array;

    public function beneficiarioAllowed(int $beneficiarioId, TenantScope $tenant, int $clienteId): bool;

}
