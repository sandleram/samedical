<?php

namespace App\Application\ImportacaoNova;

use App\Domain\ImportacaoNova\ImportacaoNova;
use App\Domain\ImportacaoNova\ImportacaoNovaRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use RuntimeException;

/**
 * Marca reprocessamento pendente. Worker/carga_* permanece deferido.
 */
final class QueueImportacaoNovaReprocess
{
    public function __construct(
        private readonly ImportacaoNovaRepositoryInterface $repository,
    ) {}

    public function execute(int $id, TenantScope $tenant, ?int $userId, DateTimeImmutable $now): ImportacaoNova
    {
        $row = $this->repository->findById($id, $tenant);
        if (! $row) {
            throw new RuntimeException('Importação Inexistente');
        }

        return $this->repository->update($id, [
            'status_processo' => 0,
            'data_atualizacao' => $now->format('Y-m-d H:i:s'),
            'usuario_atualizacao_id' => $userId,
            'avisos' => 'Reprocessamento solicitado. Job processar_arquivo / carga_* deferido na Onda E.',
        ], $tenant);
    }
}
