<?php

namespace App\Domain\Parametro;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Parametro
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nome,
        public readonly ?string $tipo,
        public readonly string $valor,
        public readonly ?int $ordenacao,
        public readonly ?int $usuarioId,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataAtualizacao,
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
            'tipo' => $this->tipo,
            'valor' => $this->valor,
            'ordenacao' => $this->ordenacao,
            'usuario_id' => $this->usuarioId,
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
