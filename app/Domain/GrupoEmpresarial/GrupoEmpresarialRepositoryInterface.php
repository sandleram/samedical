<?php

namespace App\Domain\GrupoEmpresarial;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface GrupoEmpresarialRepositoryInterface
{
    /**
     * @return PagedResult<GrupoEmpresarial>
     */
    public function search(GrupoEmpresarialSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?GrupoEmpresarial;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): GrupoEmpresarial;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): GrupoEmpresarial;
}
