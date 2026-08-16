<?php

namespace App\Domain\Beneficio;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface BeneficioRepositoryInterface
{
    /**
     * @return PagedResult<Beneficio>
     */
    public function search(BeneficioSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Beneficio;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Beneficio;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Beneficio;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant, int $clienteId): array;

    /**
     * @return array<int|string, string>
     */
    public function optionsForTenant(TenantScope $tenant): array;

}
