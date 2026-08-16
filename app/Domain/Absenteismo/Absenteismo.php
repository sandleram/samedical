<?php

namespace App\Domain\Absenteismo;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Absenteismo
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $beneficiarioId,
        public readonly ?int $empresaId,
        public readonly ?\DateTimeImmutable $dataSaida,
        public readonly ?\DateTimeImmutable $dataRetorno,
        public readonly ?string $cid,
        public readonly ?string $hospitalClinica,
        public readonly ?string $profissional,
        public readonly ?string $numCrm,
        public readonly ?int $qtdeDiasAtestado,
        public readonly ?string $observacao,
        public readonly ?string $situacao,
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
            'data_saida' => $this->dataSaida,
            'data_retorno' => $this->dataRetorno,
            'cid' => $this->cid,
            'hospital_clinica' => $this->hospitalClinica,
            'profissional' => $this->profissional,
            'num_crm' => $this->numCrm,
            'qtde_dias_atestado' => $this->qtdeDiasAtestado,
            'observacao' => $this->observacao,
            'situacao' => $this->situacao,
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
