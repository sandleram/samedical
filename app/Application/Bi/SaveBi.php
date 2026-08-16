<?php

namespace App\Application\Bi;

use App\Domain\Bi\Bi;
use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;

final class SaveBi
{
    public function __construct(
        private readonly BiRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(
        array $attributes,
        ?int $existingId,
        DateTimeImmutable $now,
        ?int $userId,
        TenantScope $tenant,
    ): Bi {
        $payload = [
            'titulo' => $attributes['titulo'] ?? null,
            'subtitulo' => $attributes['subtitulo'] ?? null,
            'link' => $attributes['link'] ?? null,
            'observacao' => $attributes['observacao'] ?? null,
            'ordem' => $attributes['ordem'] ?? null,
            'cliente_id' => $attributes['cliente_id'] ?? null,
            'grupo_empresarial_id' => $tenant->grupoEmpresarialId,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : 1,
        ];

        if ($existingId !== null) {
            return $this->repository->update($existingId, $payload, $tenant);
        }

        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');
        $payload['usuario_id'] = $userId;

        return $this->repository->create($payload);
    }
}
