<?php

namespace App\Domain\Perfil;

/**
 * Entidade de domínio Perfil.
 * Sem dependências Laravel.
 */
final class Perfil
{
    /**
     * @param  list<object{id: ?int, modulo_id: int, permissao: int}>  $perfilModulos
     */
    public function __construct(
        public readonly ?int $id,
        public readonly string $nome,
        public readonly int $tipo,
        public readonly int $status,
        public readonly ?\DateTimeImmutable $dataCadastro = null,
        public readonly ?\DateTimeImmutable $dataAtualizacao = null,
        public readonly array $perfilModulos = [],
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'nome' => $this->nome,
            'tipo' => $this->tipo,
            'status' => $this->status,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'perfilModulos' => $this->perfilModulos,
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
