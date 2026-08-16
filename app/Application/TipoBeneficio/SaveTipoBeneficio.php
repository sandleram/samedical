<?php

namespace App\Application\TipoBeneficio;

use App\Domain\TipoBeneficio\TipoBeneficio;
use App\Domain\TipoBeneficio\TipoBeneficioRepositoryInterface;
use DateTimeImmutable;

final class SaveTipoBeneficio
{
    public function __construct(
        private readonly TipoBeneficioRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now): TipoBeneficio
    {

        $payload = [
            'descricao' => $attributes['descricao'] ?? null,
            'data_cancelamento' => $attributes['data_cancelamento'] ?? null,
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : null,
        ];
        $payload = array_filter($payload, static fn ($v) => $v !== null);

        if ($existingId !== null) {
            $payload = array_merge($payload, [
            'data_atualizacao' => $now->format('Y-m-d H:i:s'),
            ]);

            return $this->repository->update($existingId, $payload);
        }

        $payload = array_merge($payload, [
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
        ]);

        return $this->repository->create($payload);
    }
}
