<?php

namespace App\Domain\Bi;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface BiRepositoryInterface
{
    /**
     * @return PagedResult<Bi>
     */
    public function search(BiSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    public function findById(int $id, TenantScope $tenant): ?Bi;

    /**
     * @param  array<string, mixed>  $data
     */
    public function create(array $data): Bi;

    /**
     * @param  array<string, mixed>  $data
     */
    public function update(int $id, array $data, TenantScope $tenant): Bi;

    /**
     * Dashboards atribuídos ao usuário (lista BI).
     *
     * @return list<array{titulo: ?string, subtitulo: ?string, link: ?string}>
     */
    public function listDashboardsForUsuario(int $usuarioId, TenantScope $tenant): array;

    public function gerencialUrl(TenantScope $tenant): string;

    public function medicoUrl(TenantScope $tenant): string;

    public function rhUrl(TenantScope $tenant): string;
}
