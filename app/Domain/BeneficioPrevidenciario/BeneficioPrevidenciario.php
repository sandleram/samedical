<?php

namespace App\Domain\BeneficioPrevidenciario;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class BeneficioPrevidenciario
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $beneficiarioId,
        public readonly ?int $empresaId,
        public readonly ?int $especieBpId,
        public readonly ?int $nb,
        public readonly ?int $nit,
        public readonly ?int $numRequerimento,
        public readonly ?string $especie,
        public readonly ?string $situacao,
        public readonly ?\DateTimeImmutable $dataInicio,
        public readonly ?\DateTimeImmutable $dataCessacao,
        public readonly ?\DateTimeImmutable $dataProximaPericia,
        public readonly ?\DateTimeImmutable $dataEntradaRequerimento,
        public readonly ?string $conclusaoPericiaMedica,
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
            'especie_bp_id' => $this->especieBpId,
            'nb' => $this->nb,
            'nit' => $this->nit,
            'num_requerimento' => $this->numRequerimento,
            'especie' => $this->especie,
            'situacao' => $this->situacao,
            'data_inicio' => $this->dataInicio,
            'data_cessacao' => $this->dataCessacao,
            'data_proxima_pericia' => $this->dataProximaPericia,
            'data_entrada_requerimento' => $this->dataEntradaRequerimento,
            'conclusao_pericia_medica' => $this->conclusaoPericiaMedica,
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
