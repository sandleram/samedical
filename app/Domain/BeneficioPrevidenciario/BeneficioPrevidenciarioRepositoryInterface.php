<?php

namespace App\Domain\BeneficioPrevidenciario;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface BeneficioPrevidenciarioRepositoryInterface
{
    /**
     * @return PagedResult<BeneficioPrevidenciario>
     */
    public function search(BeneficioPrevidenciarioSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?BeneficioPrevidenciario;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): BeneficioPrevidenciario;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): BeneficioPrevidenciario;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant, int $clienteId): array;

    public function beneficiarioAllowed(int $beneficiarioId, TenantScope $tenant, int $clienteId): bool;

}
