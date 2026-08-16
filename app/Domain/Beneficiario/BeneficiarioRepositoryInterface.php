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

    /**
     * Abas da view Cake (timeline / afastado / BP / absenteísmo).
     *
     * @return array{
     *     atendimentos: list<array<string, mixed>>,
     *     afastados: list<array<string, mixed>>,
     *     beneficiosPrevidenciarios: list<array<string, mixed>>,
     *     absenteismos: list<array<string, mixed>>
     * }
     */
    public function relatedForView(int $beneficiarioId, TenantScope $tenant): array;
}
