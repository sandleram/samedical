<?php

namespace App\Domain\MhNegociacao;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class MhNegociacao
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $mhPrestadorId,
        public readonly ?int $tipoNegocio,
        public readonly ?int $usuarioNegociadorId,
        public readonly ?int $usuarioId,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly int $status,
        public readonly ?string $prestadorNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'mh_prestador_id' => $this->mhPrestadorId,
            'tipo_negocio' => $this->tipoNegocio,
            'usuario_negociador_id' => $this->usuarioNegociadorId,
            'usuario_id' => $this->usuarioId,
            'data_cadastro' => $this->dataCadastro,
            'status' => $this->status,
            'prestador' => (object) ['nome' => $this->prestadorNome, 'id' => $this->mhPrestadorId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
