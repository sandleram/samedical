<?php

namespace App\Application\Agendamento;

use App\Domain\Agendamento\Agendamento;
use App\Domain\Agendamento\AgendamentoRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use RuntimeException;

final class SaveAgendamento
{
    public function __construct(
        private readonly AgendamentoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId, TenantScope $tenant): Agendamento
    {

        if (! $this->repository->atendimentoAllowed((int) $attributes['atendimento_id'], $tenant)) {
            throw new RuntimeException('Atendimento inválido');
        }

        $payload = [
            'atendimento_id' => $attributes['atendimento_id'] ?? null,
            'usuario_id' => $attributes['usuario_id'] ?? null,
            'usuario_agendamento_id' => $attributes['usuario_agendamento_id'] ?? null,
            'data_hora' => $attributes['data_hora'] ?? null,
            'descricao' => $attributes['descricao'] ?? null,
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
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->repository->create($payload);
    }
}
