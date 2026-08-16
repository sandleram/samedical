<?php

namespace App\Application\MhCritico;

use App\Domain\MhCritico\MhCritico;
use App\Domain\MhCritico\MhCriticoRepositoryInterface;
use DateTimeImmutable;
use InvalidArgumentException;

final class SaveMhCritico
{
    public function __construct(
        private readonly MhCriticoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?string $userId): MhCritico
    {
        $principal = (int) ($attributes['principal'] ?? 0);
        $principalId = (int) ($attributes['mh_prestador_principal_id'] ?? 0);
        $prestadorId = $principal === 1
            ? $principalId
            : (int) ($attributes['mh_prestador_id'] ?? 0);

        if ($principal === 0 && $prestadorId <= 0) {
            throw new InvalidArgumentException('Prestador Opção é obrigatório.');
        }

        $payload = [
            'principal' => $principal,
            'mh_prestador_principal_id' => $principalId,
            'mh_prestador_id' => $prestadorId,
            'opcao' => $principal === 1 ? 0 : (int) ($attributes['opcao'] ?? 0),
            'status' => (int) ($attributes['status'] ?? 0),
        ];

        if ($existingId !== null) {
            $payload['data_atualizacao'] = $now->format('Y-m-d H:i:s');
            $payload['usuario_atualizacao_id'] = $userId;

            return $this->repository->update($existingId, $payload);
        }

        $payload['ciclo'] = 0;
        $payload['status_ciclo'] = 0;
        $payload['data_cadastro'] = $now->format('Y-m-d H:i:s');
        $payload['usuario_id'] = $userId;
        $payload['data_atualizacao'] = null;

        return $this->repository->create($payload);
    }
}
