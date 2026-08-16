<?php

namespace App\Application\Parametro;

use App\Domain\Parametro\Parametro;
use App\Domain\Parametro\ParametroRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class SaveParametro
{
    public function __construct(
        private readonly ParametroRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(
        array $attributes,
        ?int $existingId,
        ?int $userId,
        DateTimeImmutable $now,
    ): Parametro {
        $tipo = trim((string) ($attributes['tipo_novo'] ?? ''));
        if ($tipo === '') {
            $tipo = trim((string) ($attributes['tipo'] ?? ''));
        }

        if ($tipo === '') {
            throw new InvalidArgumentException('O campo tipo não pode ser vazio!');
        }

        $payload = [
            'nome' => $attributes['nome'],
            'tipo' => $tipo,
            'valor' => $attributes['valor'],
            'status' => (int) $attributes['status'],
        ];

        if ($existingId !== null) {
            $payload['data_atualizacao'] = $now->format('Y-m-d H:i:s');

            return $this->repository->update($existingId, $payload);
        }

        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');
        $payload['usuario_id'] = $userId ?? 0;
        $payload['ordenacao'] = 1;

        return $this->repository->create($payload);
    }
}
