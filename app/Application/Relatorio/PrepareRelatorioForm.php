<?php

namespace App\Application\Relatorio;

use App\Domain\Relatorio\RelatorioRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class PrepareRelatorioForm
{
    public function __construct(
        private readonly RelatorioRepositoryInterface $repository,
    ) {}

    /**
     * @return array{
     *     beneficioArr: array<int|string, string>,
     *     tipoExportacaoArr: array<string, string>,
     *     anoArr: array<int|string, string>,
     *     mesArr: array<int|string, string>
     * }
     */
    public function execute(TenantScope $tenant, bool $exportacao = false): array
    {
        return [
            'beneficioArr' => $this->repository->beneficioOptions($tenant, $exportacao),
            'tipoExportacaoArr' => ['' => 'Selecione...', 'sinistro' => 'Sinistro', 'fatura' => 'Fatura'],
            'anoArr' => $this->repository->anoOptions(),
            'mesArr' => $this->repository->mesOptions(),
        ];
    }
}
