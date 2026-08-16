<?php

namespace App\Domain\Empresa;

/**
 * Entidade de domínio Empresa. Sem dependências Laravel.
 */
final class Empresa
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $clienteId,
        public readonly string $nome,
        public readonly ?string $razaoSocial,
        public readonly ?string $nomeFantasia,
        public readonly ?string $cnpj,
        public readonly ?string $inscricaoEstadual,
        public readonly ?string $inscricaoMunicipal,
        public readonly ?int $numeroFuncionarios,
        public readonly ?string $descricao,
        public readonly ?string $porte,
        public readonly ?string $faturamento,
        public readonly ?string $tipo,
        public readonly ?string $endereco,
        public readonly ?string $numero,
        public readonly ?string $complemento,
        public readonly ?string $bairro,
        public readonly ?string $cidade,
        public readonly ?string $estado,
        public readonly ?string $cep,
        public readonly ?string $telefone,
        public readonly ?string $email,
        public readonly ?string $site,
        public readonly int $status,
        public readonly ?\DateTimeImmutable $dataCadastro = null,
        public readonly ?\DateTimeImmutable $dataAtualizacao = null,
        public readonly ?string $clienteNome = null,
    ) {}

    /**
     * Acesso estilo objeto para Blade (`$row->cnpj`, `$row->cliente->nome`).
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cliente_id' => $this->clienteId,
            'nome' => $this->nome,
            'razao_social' => $this->razaoSocial,
            'nome_fantasia' => $this->nomeFantasia,
            'cnpj' => $this->cnpj,
            'inscricao_estadual' => $this->inscricaoEstadual,
            'inscricao_municipal' => $this->inscricaoMunicipal,
            'numero_funcionarios' => $this->numeroFuncionarios,
            'descricao' => $this->descricao,
            'porte' => $this->porte,
            'faturamento' => $this->faturamento,
            'tipo' => $this->tipo,
            'endereco' => $this->endereco,
            'numero' => $this->numero,
            'complemento' => $this->complemento,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'cep' => $this->cep,
            'telefone' => $this->telefone,
            'email' => $this->email,
            'site' => $this->site,
            'status' => $this->status,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'cliente' => (object) ['nome' => $this->clienteNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
