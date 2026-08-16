<?php

namespace App\Application\MhNegociacao;

use App\Domain\MhNegociacao\MhNegociacao;
use App\Domain\MhNegociacao\MhNegociacaoRepositoryInterface;
use DateTimeImmutable;

final class SaveMhNegociacao
{
    public function __construct(
        private readonly MhNegociacaoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId): MhNegociacao
    {
        $payload = [
            'mh_prestador_id' => (int) ($attributes['mh_prestador_id'] ?? 0),
            'tipo_negocio' => (int) ($attributes['tipo_negocio'] ?? 0),
            'usuario_negociador_id' => (int) ($attributes['usuario_negociador_id'] ?? $userId ?? 0),
            'status' => (int) ($attributes['status'] ?? 0),
        ];

        if ($existingId !== null) {
            return $this->repository->update($existingId, $payload);
        }

        $payload['usuario_id'] = $userId;
        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');

        return $this->repository->create($payload);
    }
}
