<?php

namespace App\Domain\Procedimento;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Procedimento
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $codProcedimento,
        public readonly string $dsProcedimento,
        public readonly ?string $tipoProcedimento,
        public readonly ?string $grupo,
        public readonly ?string $subgrupo,
        public readonly ?string $grupoDeExames,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataAtualizacao,
        public readonly int $status,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cod_procedimento' => $this->codProcedimento,
            'ds_procedimento' => $this->dsProcedimento,
            'tipo_procedimento' => $this->tipoProcedimento,
            'Grupo' => $this->grupo,
            'Subgrupo' => $this->subgrupo,
            'Grupo de Exames' => $this->grupoDeExames,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'status' => $this->status,
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
