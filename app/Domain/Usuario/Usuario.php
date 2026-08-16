<?php

namespace App\Domain\Usuario;

/**
 * Entidade de domínio Usuario (tabela legado `usuario`).
 * Sem dependências Laravel.
 */
final class Usuario
{
    /**
     * @param  list<int>  $clienteIds
     * @param  list<int>  $biIds
     */
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $grupoEmpresarialId,
        public readonly ?int $perfilId,
        public readonly string $nome,
        public readonly ?string $apelido,
        public readonly string $usuario,
        public readonly string $email,
        public readonly ?string $emailGestao,
        public readonly ?string $sexo,
        public readonly ?string $rg,
        public readonly ?string $cpf,
        public readonly ?\DateTimeImmutable $dataNascimento,
        public readonly ?string $tel1Tipo,
        public readonly ?string $tel1,
        public readonly ?string $tel2Tipo,
        public readonly ?string $tel2,
        public readonly ?string $tel3Tipo,
        public readonly ?string $tel3,
        public readonly ?string $observacao,
        public readonly int $status,
        public readonly ?\DateTimeImmutable $dataCadastro = null,
        public readonly ?\DateTimeImmutable $dataAtualizacao = null,
        public readonly ?string $perfilNome = null,
        public readonly ?string $grupoEmpresarialNome = null,
        public readonly ?string $usuarioCriadorNome = null,
        public readonly array $clienteIds = [],
        public readonly array $biIds = [],
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'grupo_empresarial_id' => $this->grupoEmpresarialId,
            'perfil_id' => $this->perfilId,
            'nome' => $this->nome,
            'apelido' => $this->apelido,
            'usuario' => $this->usuario,
            'email' => $this->email,
            'email_gestao' => $this->emailGestao,
            'sexo' => $this->sexo,
            'rg' => $this->rg,
            'cpf' => $this->cpf,
            'data_nascimento' => $this->dataNascimento,
            'tel1_tipo' => $this->tel1Tipo,
            'tel1' => $this->tel1,
            'tel2_tipo' => $this->tel2Tipo,
            'tel2' => $this->tel2,
            'tel3_tipo' => $this->tel3Tipo,
            'tel3' => $this->tel3,
            'observacao' => $this->observacao,
            'status' => $this->status,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'perfil' => (object) ['nome' => $this->perfilNome],
            'grupoEmpresarial' => (object) ['nome' => $this->grupoEmpresarialNome],
            'usuarioCriador' => (object) ['nome' => $this->usuarioCriadorNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
