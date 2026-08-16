<?php

namespace App\Domain\Subfatura;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface SubfaturaRepositoryInterface
{
    /**
     * @return PagedResult<Subfatura>
     */
    public function search(SubfaturaSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Subfatura;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Subfatura;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Subfatura;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant, int $clienteId): array;

}
