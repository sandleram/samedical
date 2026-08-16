<?php

namespace App\Domain\Afastado;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Afastado
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $beneficiarioId,
        public readonly ?int $empresaId,
        public readonly ?string $situacao,
        public readonly ?\DateTimeImmutable $dataInicioAfastamento,
        public readonly ?\DateTimeImmutable $dataFimAfastamento,
        public readonly ?string $cid,
        public readonly ?string $tipoAfastamento,
        public readonly ?string $assistenciaMedica,
        public readonly ?string $planoAssistenciaMedica,
        public readonly ?int $acaoTrabalhista,
        public readonly ?int $acaoInss,
        public readonly ?int $limboPrevidenciario,
        public readonly int $status,
        public readonly ?string $beneficiarioNome = null,
        public readonly ?string $empresaLabel = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'beneficiario_id' => $this->beneficiarioId,
            'empresa_id' => $this->empresaId,
            'situacao' => $this->situacao,
            'data_inicio_afastamento' => $this->dataInicioAfastamento,
            'data_fim_afastamento' => $this->dataFimAfastamento,
            'cid' => $this->cid,
            'tipo_afastamento' => $this->tipoAfastamento,
            'assistencia_medica' => $this->assistenciaMedica,
            'plano_assistencia_medica' => $this->planoAssistenciaMedica,
            'acao_trabalhista' => $this->acaoTrabalhista,
            'acao_inss' => $this->acaoInss,
            'limbo_previdenciario' => $this->limboPrevidenciario,
            'status' => $this->status,
            'beneficiario' => (object) ['nome' => $this->beneficiarioNome, 'id' => $this->beneficiarioId],
            'empresa' => (object) ['razao_social' => $this->empresaLabel, 'id' => $this->empresaId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
