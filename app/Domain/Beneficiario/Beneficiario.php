<?php

namespace App\Domain\Beneficiario;

/**
 * Entidade de domínio (dados de beneficiário + relações de leitura).
 * Sem dependências Laravel.
 */
final class Beneficiario
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $clienteId,
        public readonly ?int $empresaId,
        public readonly string $nome,
        public readonly ?string $nomeSocial,
        public readonly ?string $email,
        public readonly ?string $cpf,
        public readonly ?string $rg,
        public readonly ?string $pis,
        public readonly ?string $sexo,
        public readonly ?string $estadoCivil,
        public readonly ?\DateTimeImmutable $dataNascimento,
        public readonly ?int $altura,
        public readonly ?float $peso,
        public readonly ?float $imc,
        public readonly ?string $endereco,
        public readonly ?string $bairro,
        public readonly ?string $cidade,
        public readonly ?string $estado,
        public readonly ?string $cep,
        public readonly ?string $telefoneTipo,
        public readonly ?string $telefone,
        public readonly ?string $telefone1Tipo,
        public readonly ?string $telefone1,
        public readonly ?string $agencia,
        public readonly ?string $conta,
        public readonly ?string $tipoDeConta,
        public readonly ?string $profissao,
        public readonly ?string $ocupacao,
        public readonly ?string $pessoaPoliticamenteExposta,
        public readonly ?string $atividadePerigosa,
        public readonly ?string $possuiDeficiencia,
        public readonly ?string $observacao,
        public readonly ?string $situacao,
        public readonly ?string $beneficio,
        public readonly ?float $valorDoSeguro,
        public readonly ?string $codMatricula,
        public readonly int $status,
        public readonly ?string $clienteNome = null,
        public readonly ?string $empresaRazaoSocial = null,
        public readonly ?string $empresaNome = null,
        public readonly ?string $grupoEmpresarialNome = null,
    ) {}

    /**
     * Acesso estilo objeto para Blade (`$row->cpf`, `$row->cliente->nome`).
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cliente_id' => $this->clienteId,
            'empresa_id' => $this->empresaId,
            'nome' => $this->nome,
            'nome_social' => $this->nomeSocial,
            'email' => $this->email,
            'cpf' => $this->cpf,
            'rg' => $this->rg,
            'pis' => $this->pis,
            'sexo' => $this->sexo,
            'estado_civil' => $this->estadoCivil,
            'data_nascimento' => $this->dataNascimento,
            'altura' => $this->altura,
            'peso' => $this->peso,
            'imc' => $this->imc,
            'endereco' => $this->endereco,
            'bairro' => $this->bairro,
            'cidade' => $this->cidade,
            'estado' => $this->estado,
            'cep' => $this->cep,
            'telefone_tipo' => $this->telefoneTipo,
            'telefone' => $this->telefone,
            'telefone1_tipo' => $this->telefone1Tipo,
            'telefone1' => $this->telefone1,
            'agencia' => $this->agencia,
            'conta' => $this->conta,
            'tipo_de_conta' => $this->tipoDeConta,
            'profissao' => $this->profissao,
            'ocupacao' => $this->ocupacao,
            'pessoa_politicamente_exposta' => $this->pessoaPoliticamenteExposta,
            'realiza_alguma_atividade_perigosa_na_profissao' => $this->atividadePerigosa,
            'possui_deficiencia' => $this->possuiDeficiencia,
            'observacao' => $this->observacao,
            'situacao' => $this->situacao,
            'beneficio' => $this->beneficio,
            'valor_do_seguro' => $this->valorDoSeguro,
            'cod_matricula' => $this->codMatricula,
            'status' => $this->status,
            'cliente' => (object) [
                'nome' => $this->clienteNome,
                'grupoEmpresarial' => (object) ['nome' => $this->grupoEmpresarialNome],
            ],
            'empresa' => (object) [
                'razao_social' => $this->empresaRazaoSocial,
                'nome' => $this->empresaNome,
            ],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
