<?php

namespace App\Domain\Operadora;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Operadora
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nome,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataCancelamento,
        public readonly int $status,
    ) {}

    /**
     * Acesso estilo objeto para Blade (`$row->nome`, `$row->data_cadastro`).
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'nome' => $this->nome,
            'data_cadastro' => $this->dataCadastro,
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
