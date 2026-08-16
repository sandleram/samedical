<?php

namespace App\Domain\Subfatura;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Subfatura
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $beneficioId,
        public readonly string $descricao,
        public readonly string $codigo,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataCancelamento,
        public readonly int $status,
        public readonly ?string $beneficioDescricao = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'beneficio_id' => $this->beneficioId,
            'descricao' => $this->descricao,
            'codigo' => $this->codigo,
            'data_cadastro' => $this->dataCadastro,
            'data_cancelamento' => $this->dataCancelamento,
            'status' => $this->status,
            'beneficio' => (object) ['descricao' => $this->beneficioDescricao, 'id' => $this->beneficioId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
