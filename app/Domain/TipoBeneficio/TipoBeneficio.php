<?php

namespace App\Domain\TipoBeneficio;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class TipoBeneficio
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $descricao,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataAtualizacao,
        public readonly ?\DateTimeImmutable $dataCancelamento,
        public readonly int $status,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'descricao' => $this->descricao,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'data_cancelamento' => $this->dataCancelamento,
            'status' => $this->status,
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
