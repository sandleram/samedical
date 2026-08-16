<?php

namespace App\Application\MhCriticoHistorico;

use App\Domain\MhCriticoHistorico\MhCriticoHistorico;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoRepositoryInterface;
use DateTimeImmutable;
use RuntimeException;

final class SaveMhCriticoHistorico
{
    public function __construct(
        private readonly MhCriticoHistoricoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, int $mhCriticoId, ?int $existingId, DateTimeImmutable $now, ?int $userId): MhCriticoHistorico
    {
        if (! $this->repository->criticoExists($mhCriticoId)) {
            throw new RuntimeException('Crítico Histórico  Inexistente');
        }

        $payload = [
            'ciclo' => (int) ($attributes['ciclo'] ?? 0),
            'status_ciclo' => (int) ($attributes['status_ciclo'] ?? 0),
            'descricao' => (string) ($attributes['descricao'] ?? ''),
            'status' => (int) ($attributes['status'] ?? 0),
        ];

        if ($existingId !== null) {
            return $this->repository->update($mhCriticoId, $existingId, $payload);
        }

        $payload['mh_critico_id'] = $mhCriticoId;
        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');
        $payload['usuario_id'] = $userId;

        return $this->repository->create($payload);
    }
}
