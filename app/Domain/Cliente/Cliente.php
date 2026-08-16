<?php

namespace App\Domain\Cliente;

/**
 * Entidade de domínio Cliente. Sem dependências Laravel.
 */
final class Cliente
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $grupoEmpresarialId,
        public readonly string $nome,
        public readonly ?string $imgLogo,
        public readonly int $status,
        public readonly ?\DateTimeImmutable $dataCadastro = null,
        public readonly ?\DateTimeImmutable $dataAtualizacao = null,
        public readonly ?\DateTimeImmutable $dataCancelamento = null,
        public readonly ?string $grupoEmpresarialNome = null,
    ) {}

    /**
     * Acesso estilo objeto para Blade (`$row->nome`, `$row->grupoEmpresarial->nome`).
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'grupo_empresarial_id' => $this->grupoEmpresarialId,
            'nome' => $this->nome,
            'img_logo' => $this->imgLogo,
            'status' => $this->status,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'data_cancelamento' => $this->dataCancelamento,
            'grupoEmpresarial' => (object) ['nome' => $this->grupoEmpresarialNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
