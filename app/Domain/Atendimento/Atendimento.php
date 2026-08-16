<?php

namespace App\Domain\Atendimento;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Atendimento
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $beneficiarioId,
        public readonly ?int $usuarioId,
        public readonly ?int $tipoAtendimento,
        public readonly ?string $cid,
        public readonly ?string $descricao,
        public readonly ?int $formaAtendimento,
        public readonly ?int $statusAtendimento,
        public readonly ?\DateTimeImmutable $dataConclusao,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly int $status,
        public readonly ?string $beneficiarioNome = null,
        public readonly ?string $usuarioNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'beneficiario_id' => $this->beneficiarioId,
            'usuario_id' => $this->usuarioId,
            'tipo_atendimento' => $this->tipoAtendimento,
            'cid' => $this->cid,
            'descricao' => $this->descricao,
            'forma_atendimento' => $this->formaAtendimento,
            'status_atendimento' => $this->statusAtendimento,
            'data_conclusao' => $this->dataConclusao,
            'data_cadastro' => $this->dataCadastro,
            'status' => $this->status,
            'beneficiario' => (object) ['nome' => $this->beneficiarioNome, 'id' => $this->beneficiarioId],
            'usuario' => (object) ['nome' => $this->usuarioNome, 'id' => $this->usuarioId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
