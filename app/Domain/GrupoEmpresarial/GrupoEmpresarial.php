<?php

namespace App\Domain\GrupoEmpresarial;

/**
 * Entidade de domínio Grupo Empresarial. Sem dependências Laravel.
 */
final class GrupoEmpresarial
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nome,
        public readonly ?string $imgLogo,
        public readonly ?string $bi,
        public readonly ?string $cor,
        public readonly int $status,
        public readonly ?\DateTimeImmutable $dataCadastro = null,
        public readonly ?\DateTimeImmutable $dataCancelamento = null,
    ) {}

    /**
     * Acesso estilo objeto para Blade (`$row->bi`, `$row->cor`).
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'nome' => $this->nome,
            'img_logo' => $this->imgLogo,
            'bi' => $this->bi,
            'cor' => $this->cor,
            'status' => $this->status,
            'data_cadastro' => $this->dataCadastro,
            'data_cancelamento' => $this->dataCancelamento,
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
