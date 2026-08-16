<?php

namespace App\Application\BeneficioPrevidenciario;

use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciario;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use RuntimeException;

final class SaveBeneficioPrevidenciario
{
    public function __construct(
        private readonly BeneficioPrevidenciarioRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId, int $clienteId, TenantScope $tenant): BeneficioPrevidenciario
    {

        $benefId = (int) ($attributes['beneficiario_id'] ?? 0);
        if (! $this->repository->beneficiarioAllowed($benefId, $tenant, $clienteId)) {
            throw new RuntimeException('Beneficiário inválido');
        }

        $payload = [
            'beneficiario_id' => $attributes['beneficiario_id'] ?? null,
            'empresa_id' => $attributes['empresa_id'] ?? null,
            'especie_bp_id' => $attributes['especie_bp_id'] ?? null,
            'nb' => $attributes['nb'] ?? null,
            'nit' => $attributes['nit'] ?? null,
            'num_requerimento' => $attributes['num_requerimento'] ?? null,
            'especie' => $attributes['especie'] ?? null,
            'situacao' => $attributes['situacao'] ?? null,
            'data_inicio' => $attributes['data_inicio'] ?? null,
            'data_cessacao' => $attributes['data_cessacao'] ?? null,
            'data_proxima_pericia' => $attributes['data_proxima_pericia'] ?? null,
            'data_entrada_requerimento' => $attributes['data_entrada_requerimento'] ?? null,
            'conclusao_pericia_medica' => $attributes['conclusao_pericia_medica'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];
        $payload = array_filter($payload, static fn ($v) => $v !== null);

        if ($existingId !== null) {
            $payload = array_merge($payload, [
            'usuario_atualizacao_id' => $userId,
            'data_atualizacao' => $now->format('Y-m-d'),
            ]);

            return $this->repository->update($existingId, $payload, $tenant);
        }

        $payload = array_merge($payload, [
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : 1,
        ]);

        return $this->repository->create($payload);
    }
}
