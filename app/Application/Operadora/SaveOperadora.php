<?php

namespace App\Application\Operadora;

use App\Domain\Operadora\Operadora;
use App\Domain\Operadora\OperadoraRepositoryInterface;
use DateTimeImmutable;

final class SaveOperadora
{
    public function __construct(
        private readonly OperadoraRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now): Operadora
    {
        $payload = [
            'nome' => $attributes['nome'],
            'data_cancelamento' => $attributes['data_cancelamento'] ?? null,
            'status' => (int) $attributes['status'],
        ];

        if ($existingId !== null) {
            return $this->repository->update($existingId, $payload);
        }

        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');

        return $this->repository->create($payload);
    }
}
