<?php

namespace App\Application\Beneficio;

use App\Domain\Beneficio\Beneficio;
use App\Domain\Beneficio\BeneficioRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;

final class SaveBeneficio
{
    public function __construct(
        private readonly BeneficioRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, int $clienteId, TenantScope $tenant): Beneficio
    {

        $payload = [
            'descricao' => $attributes['descricao'] ?? null,
            'breakeven' => $attributes['breakeven'] ?? null,
            'contrato' => $attributes['contrato'] ?? null,
            'operadora_id' => $attributes['operadora_id'] ?? null,
            'tipo_beneficio_id' => $attributes['tipo_beneficio_id'] ?? null,
            'data_cancelamento' => $attributes['data_cancelamento'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];
        $payload = array_filter($payload, static fn ($v) => $v !== null);

        if ($existingId !== null) {
            $payload = array_merge($payload, [
            'data_atualizacao' => $now->format('Y-m-d H:i:s'),
            ]);

            return $this->repository->update($existingId, $payload, $tenant);
        }

        $payload = array_merge($payload, [
            'cliente_id' => $clienteId,
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->repository->create($payload);
    }
}
