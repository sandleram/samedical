<?php

namespace App\Application\Absenteismo;

use App\Domain\Absenteismo\Absenteismo;
use App\Domain\Absenteismo\AbsenteismoRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use RuntimeException;

final class SaveAbsenteismo
{
    public function __construct(
        private readonly AbsenteismoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId, int $clienteId, TenantScope $tenant): Absenteismo
    {

        $benefId = (int) ($attributes['beneficiario_id'] ?? 0);
        if (! $this->repository->beneficiarioAllowed($benefId, $tenant, $clienteId)) {
            throw new RuntimeException('Beneficiário inválido');
        }

        $payload = [
            'beneficiario_id' => $attributes['beneficiario_id'] ?? null,
            'empresa_id' => $attributes['empresa_id'] ?? null,
            'data_saida' => $attributes['data_saida'] ?? null,
            'data_retorno' => $attributes['data_retorno'] ?? null,
            'cid' => $attributes['cid'] ?? null,
            'hospital_clinica' => $attributes['hospital_clinica'] ?? null,
            'profissional' => $attributes['profissional'] ?? null,
            'num_crm' => $attributes['num_crm'] ?? null,
            'qtde_dias_atestado' => $attributes['qtde_dias_atestado'] ?? null,
            'observacao' => $attributes['observacao'] ?? null,
            'situacao' => $attributes['situacao'] ?? null,
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
