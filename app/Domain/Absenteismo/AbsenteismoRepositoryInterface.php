<?php

namespace App\Domain\Absenteismo;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface AbsenteismoRepositoryInterface
{
    /**
     * @return PagedResult<Absenteismo>
     */
    public function search(AbsenteismoSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Absenteismo;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Absenteismo;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Absenteismo;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant, int $clienteId): array;

    public function beneficiarioAllowed(int $beneficiarioId, TenantScope $tenant, int $clienteId): bool;

}
