<?php

namespace App\Domain\Atendimento;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface AtendimentoRepositoryInterface
{
    /**
     * @return PagedResult<Atendimento>
     */
    public function search(AtendimentoSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Atendimento;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Atendimento;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Atendimento;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant): array;

    public function beneficiarioAllowed(int $beneficiarioId, TenantScope $tenant, int $clienteId): bool;

}
