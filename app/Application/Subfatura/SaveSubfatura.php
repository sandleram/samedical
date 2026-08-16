<?php

namespace App\Application\Subfatura;

use App\Domain\Subfatura\Subfatura;
use App\Domain\Subfatura\SubfaturaRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;

final class SaveSubfatura
{
    public function __construct(
        private readonly SubfaturaRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, int $clienteId, TenantScope $tenant): Subfatura
    {

        $payload = [
            'beneficio_id' => $attributes['beneficio_id'] ?? null,
            'descricao' => $attributes['descricao'] ?? null,
            'codigo' => $attributes['codigo'] ?? null,
            'data_cancelamento' => $attributes['data_cancelamento'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];
        $payload = array_filter($payload, static fn ($v) => $v !== null);

        if ($existingId !== null) {

            return $this->repository->update($existingId, $payload, $tenant);
        }

        $payload = array_merge($payload, [
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->repository->create($payload);
    }
}
