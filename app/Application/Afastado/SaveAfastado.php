<?php

namespace App\Application\Afastado;

use App\Domain\Afastado\Afastado;
use App\Domain\Afastado\AfastadoRepositoryInterface;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use RuntimeException;

final class SaveAfastado
{
    public function __construct(
        private readonly AfastadoRepositoryInterface $repository,
    ) {}

    /**
     * @param  array<string, mixed>  $attributes
     */
    public function execute(array $attributes, ?int $existingId, DateTimeImmutable $now, ?int $userId, int $clienteId, TenantScope $tenant): Afastado
    {

        $benefId = (int) ($attributes['beneficiario_id'] ?? 0);
        if (! $this->repository->beneficiarioAllowed($benefId, $tenant, $clienteId)) {
            throw new RuntimeException('Beneficiário inválido');
        }

        $payload = [
            'beneficiario_id' => $attributes['beneficiario_id'] ?? null,
            'empresa_id' => $attributes['empresa_id'] ?? null,
            'situacao' => $attributes['situacao'] ?? null,
            'data_inicio_afastamento' => $attributes['data_inicio_afastamento'] ?? null,
            'data_fim_afastamento' => $attributes['data_fim_afastamento'] ?? null,
            'cid' => $attributes['cid'] ?? null,
            'tipo_afastamento' => $attributes['tipo_afastamento'] ?? null,
            'assistencia_medica' => $attributes['assistencia_medica'] ?? null,
            'plano_assistencia_medica' => $attributes['plano_assistencia_medica'] ?? null,
            'acao_trabalhista' => $attributes['acao_trabalhista'] ?? null,
            'acao_inss' => $attributes['acao_inss'] ?? null,
            'limbo_previdenciario' => $attributes['limbo_previdenciario'] ?? null,
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
            'usuario_criador_id' => $userId,
            'data_cadastro' => $now->format('Y-m-d H:i:s'),
            'status' => isset($attributes['status']) ? (int) $attributes['status'] : 1,
        ]);

        return $this->repository->create($payload);
    }
}
