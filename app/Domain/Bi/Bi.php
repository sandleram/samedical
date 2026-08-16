<?php

namespace App\Domain\Bi;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Bi
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $grupoEmpresarialId,
        public readonly ?int $clienteId,
        public readonly ?string $titulo,
        public readonly ?string $subtitulo,
        public readonly ?string $link,
        public readonly ?string $observacao,
        public readonly ?int $ordem,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?int $usuarioId,
        public readonly int $status,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'grupo_empresarial_id' => $this->grupoEmpresarialId,
            'cliente_id' => $this->clienteId,
            'titulo' => $this->titulo,
            'subtitulo' => $this->subtitulo,
            'link' => $this->link,
            'observacao' => $this->observacao,
            'ordem' => $this->ordem,
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
