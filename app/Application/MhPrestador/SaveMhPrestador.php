<?php

namespace App\Application\MhPrestador;

use App\Domain\MhPrestador\MhPrestador;
use App\Domain\MhPrestador\MhPrestadorRepositoryInterface;
use DateTimeImmutable;

final class SaveMhPrestador
{
    public function __construct(
        private readonly MhPrestadorRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId): MhPrestador
    {
        $payload = [
            'nome' => $attributes['nome'] ?? null,
            'id_hubspot' => $attributes['id_hubspot'] ?? null,
            'cidade' => $attributes['cidade'] ?? null,
            'estado' => $attributes['estado'] ?? null,
            'praca' => $attributes['praca'] ?? null,
            'atividade' => $attributes['atividade'] ?? null,
            'descricao' => $attributes['descricao'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];

        if ($existingId !== null) {
            return $this->repository->update($existingId, $payload);
        }

        $payload['usuario_id'] = $userId;
        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');

        return $this->repository->create($payload);
    }
}
