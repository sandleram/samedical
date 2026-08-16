<?php

namespace App\Domain\MhCritico;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class MhCritico
{
    /**
     * @param  object{count: callable(): int}|null  $historicos
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $mhPrestadorId,
        public readonly ?int $mhPrestadorPrincipalId,
        public readonly ?int $principal,
        public readonly ?string $nome,
        public readonly ?int $opcao,
        public readonly ?int $ciclo,
        public readonly ?int $statusCiclo,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataAtualizacao,
        public readonly ?string $usuarioId,
        public readonly ?string $usuarioAtualizacaoId,
        public readonly int $status,
        public readonly ?string $prestadorNome = null,
        public readonly ?string $prestadorCidade = null,
        public readonly ?string $prestadorEstado = null,
        public readonly ?string $prestadorPrincipalNome = null,
        public readonly int $historicosCount = 0,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'mh_prestador_id' => $this->mhPrestadorId,
            'mh_prestador_principal_id' => $this->mhPrestadorPrincipalId,
            'principal' => $this->principal,
            'nome' => $this->nome,
            'opcao' => $this->opcao,
            'ciclo' => $this->ciclo,
            'status_ciclo' => $this->statusCiclo,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'usuario_id' => $this->usuarioId,
            'usuario_atualizacao_id' => $this->usuarioAtualizacaoId,
            'status' => $this->status,
            'prestador' => (object) [
                'nome' => $this->prestadorNome,
                'cidade' => $this->prestadorCidade,
                'estado' => $this->prestadorEstado,
                'id' => $this->mhPrestadorId,
            ],
            'prestadorPrincipal' => (object) [
                'nome' => $this->prestadorPrincipalNome,
                'id' => $this->mhPrestadorPrincipalId,
            ],
            'historicos' => new class($this->historicosCount)
            {
                public function __construct(private readonly int $count) {}

                public function count(): int
                {
                    return $this->count;
                }
            },
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
