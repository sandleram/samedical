<?php

namespace App\Domain\Relatorio;

use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;

interface RelatorioRepositoryInterface
{
    /**
     * @return PagedResult<RelatorioAfastadoRow>
     */
    public function searchAfastados(RelatorioAfastadoSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    /**
     * @return PagedResult<RelatorioBeneficiarioRow>
     */
    public function searchBeneficiarios(RelatorioBeneficiarioSearchCriteria $criteria, TenantScope $tenant): PagedResult;

    /**
     * @return PagedResult<RelatorioAtendimentoPendenteRow>
     */
    public function searchAtendimentosPendentes(RelatorioAtendimentoPendenteSearchCriteria $criteria): PagedResult;

    /**
     * @return array<int|string, string>
     */
    public function beneficioOptions(TenantScope $tenant, bool $withPlaceholder = false): array;

    /**
     * @return array<int|string, string>
     */
    public function anoOptions(): array;

    /**
     * @return array<int|string, string>
     */
    public function mesOptions(): array;
}
