<?php

namespace App\Domain\MhCriticoHistorico;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class MhCriticoHistorico
{
    public function __construct(
        public readonly ?int $id,
        public readonly int $mhCriticoId,
        public readonly ?int $ciclo,
        public readonly ?int $statusCiclo,
        public readonly ?string $descricao,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?int $usuarioId,
        public readonly int $status,
        public readonly ?string $criticoPrestadorNome = null,
        public readonly ?string $criticoPrestadorCidade = null,
        public readonly ?string $criticoPrestadorEstado = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'mh_critico_id' => $this->mhCriticoId,
            'ciclo' => $this->ciclo,
            'status_ciclo' => $this->statusCiclo,
            'descricao' => $this->descricao,
            'data_cadastro' => $this->dataCadastro,
            'usuario_id' => $this->usuarioId,
            'status' => $this->status,
            'critico' => (object) [
                'id' => $this->mhCriticoId,
                'prestador' => (object) [
                    'nome' => $this->criticoPrestadorNome,
                    'cidade' => $this->criticoPrestadorCidade,
                    'estado' => $this->criticoPrestadorEstado,
                ],
            ],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
