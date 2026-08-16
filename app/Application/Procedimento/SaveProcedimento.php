<?php

namespace App\Application\Procedimento;

use App\Domain\Procedimento\Procedimento;
use App\Domain\Procedimento\ProcedimentoRepositoryInterface;
use DateTimeImmutable;

final class SaveProcedimento
{
    public function __construct(
        private readonly ProcedimentoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId): Procedimento
    {

        $payload = [
            'cod_procedimento' => $attributes['cod_procedimento'] ?? null,
            'ds_procedimento' => $attributes['ds_procedimento'] ?? null,
            'tipo_procedimento' => $attributes['tipo_procedimento'] ?? null,
            'Grupo' => $attributes['Grupo'] ?? null,
            'Subgrupo' => $attributes['Subgrupo'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];
        $payload = array_filter($payload, static fn ($v) => $v !== null);

        if ($existingId !== null) {
            $payload = array_merge($payload, [
            'data_atualizacao' => $now->format('Y-m-d H:i:s'),
            'usuario_atualizacao_id' => $userId,
            ]);

            return $this->repository->update($existingId, $payload);
        }

        $payload = array_merge($payload, [
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
            'usuario_id' => $userId,
            'Grupo' => $attributes['Grupo'] ?? '',
            'Subgrupo' => $attributes['Subgrupo'] ?? '',
            'Grupo de Exames' => $attributes['Grupo de Exames'] ?? '',
        ]);

        return $this->repository->create($payload);
    }
}
