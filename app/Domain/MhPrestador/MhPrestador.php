<?php

namespace App\Domain\MhPrestador;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class MhPrestador
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $idHubspot,
        public readonly string $nome,
        public readonly ?string $cidade,
        public readonly ?string $estado,
        public readonly ?string $praca,
        public readonly ?string $atividade,
        public readonly ?string $descricao,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?int $usuarioId,
        public readonly int $status,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'id_hubspot' => $this->idHubspot,
            'nome' => $this->nome,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'praca' => $this->praca,
            'atividade' => $this->atividade,
            'descricao' => $this->descricao,
            'data_cadastro' => $this->dataCadastro,
            'usuario_id' => $this->usuarioId,
            'status' => $this->status,
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
