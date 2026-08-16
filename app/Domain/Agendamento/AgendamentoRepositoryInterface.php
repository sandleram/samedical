<?php

namespace App\Domain\Agendamento;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface AgendamentoRepositoryInterface
{
    /**
     * @return PagedResult<Agendamento>
     */
    public function search(AgendamentoSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Agendamento;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Agendamento;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Agendamento;

    /**
     * @return array<string, mixed>
     */
    public function formOptions(TenantScope $tenant): array;

    public function atendimentoAllowed(int $atendimentoId, TenantScope $tenant): bool;

    /**
     * @return array<int|string, string>
     */
    public function usuarioOptions(): array;

}
