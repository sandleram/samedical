<?php

namespace App\Application\Atendimento;

use App\Domain\Atendimento\Atendimento;
use App\Domain\Atendimento\AtendimentoRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use RuntimeException;

final class SaveAtendimento
{
    public function __construct(
        private readonly AtendimentoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId, int $clienteId, TenantScope $tenant): Atendimento
    {

        $benefId = (int) ($attributes['beneficiario_id'] ?? 0);
        if (! $this->repository->beneficiarioAllowed($benefId, $tenant, $clienteId)) {
            throw new RuntimeException('Beneficiário inválido');
        }

        $payload = [
            'beneficiario_id' => $attributes['beneficiario_id'] ?? null,
            'tipo_atendimento' => $attributes['tipo_atendimento'] ?? null,
            'cid' => $attributes['cid'] ?? null,
            'descricao' => $attributes['descricao'] ?? null,
            'forma_atendimento' => $attributes['forma_atendimento'] ?? null,
            'status_atendimento' => $attributes['status_atendimento'] ?? null,
            'data_conclusao' => $attributes['data_conclusao'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];
        $payload = array_filter($payload, static fn ($v) => $v !== null);

        if ($existingId !== null) {
            $payload = array_merge($payload, [
            'usuario_atualizacao_id' => $userId,
            'data_atualizacao' => $now->format('Y-m-d H:i:s'),
            ]);

            return $this->repository->update($existingId, $payload, $tenant);
        }

        $payload = array_merge($payload, [
            'usuario_id' => $userId,
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : 1,
        ]);

        return $this->repository->create($payload);
    }
}
