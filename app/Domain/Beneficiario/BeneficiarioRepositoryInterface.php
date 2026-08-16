<?php

namespace App\Domain\Beneficiario;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface BeneficiarioRepositoryInterface
{
    /**
     * @return PagedResult<Beneficiario>
     */
    public function search(BeneficiarioSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Beneficiario;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Beneficiario;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Beneficiario;
}
