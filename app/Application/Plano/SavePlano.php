<?php

namespace App\Application\Plano;

use App\Domain\Plano\Plano;
use App\Domain\Plano\PlanoRepositoryInterface;
use DateTimeImmutable;

final class SavePlano
{
    public function __construct(
        private readonly PlanoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(
        array $attributes,
        ?int $existingId,
        ?int $clienteId,
        DateTimeImmutable $now,
    ): Plano {
        $payload = [
            'nome' => $attributes['nome'],
            'codigo_operadora' => $attributes['codigo_operadora'] ?? '',
            'operadora_id' => $attributes['operadora_id'] ?? null,
            'tipo_beneficio_id' => $attributes['tipo_beneficio_id'] ?? null,
            'status' => (int) $attributes['status'],
        ];

        if ($existingId !== null) {
            return $this->repository->update($existingId, $payload);
        }

        $operadoraId = isset($payload['operadora_id']) ? (int) $payload['operadora_id'] : null;
        $tipoBeneficioId = isset($payload['tipo_beneficio_id']) ? (int) $payload['tipo_beneficio_id'] : null;
        if ($operadoraId === 0) {
            $operadoraId = null;
        }
        if ($tipoBeneficioId === 0) {
            $tipoBeneficioId = null;
        }

        $payload['cliente_id'] = $clienteId;
        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');
        $payload['ordem'] = $this->repository->nextOrdem($clienteId, $operadoraId, $tipoBeneficioId);

        return $this->repository->create($payload);
    }
}
