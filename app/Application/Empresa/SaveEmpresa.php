<?php

namespace App\Application\Empresa;

use App\Domain\Empresa\Empresa;
use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\Shared\TenantScope;

final class SaveEmpresa
{
    public function __construct(
        private readonly EmpresaRepositoryInterface $repository,
    ) {}

    public function execute(SaveEmpresaInput $input, TenantScope $tenant): Empresa
    {
        $attrs = $input->attributes;
        $cnpj = preg_replace('/\D+/', '', (string) ($attrs['cnpj'] ?? '')) ?? '';

        $payload = [
            'nome' => $attrs['nome'],
            'razao_social' => $attrs['razao_social'] ?? null,
            'nome_fantasia' => $attrs['nome_fantasia'] ?? null,
            'cnpj' => $cnpj,
            'inscricao_estadual' => $attrs['inscricao_estadual'] ?? null,
            'inscricao_municipal' => $attrs['inscricao_municipal'] ?? null,
            'numero_funcionarios' => $attrs['numero_funcionarios'] ?? null,
            'descricao' => $attrs['descricao'] ?? null,
            'porte' => $attrs['porte'] ?? null,
            'faturamento' => $attrs['faturamento'] ?? null,
            'tipo' => $attrs['tipo'] ?? null,
            'endereco' => $attrs['endereco'] ?? null,
            'numero' => $attrs['numero'] ?? null,
            'complemento' => $attrs['complemento'] ?? null,
            'bairro' => $attrs['bairro'] ?? null,
            'cidade' => $attrs['cidade'] ?? null,
            'estado' => $attrs['estado'] ?? null,
            'cep' => $attrs['cep'] ?? null,
            'telefone' => $attrs['telefone'] ?? null,
            'email' => $attrs['email'] ?? null,
            'site' => $attrs['site'] ?? null,
            'status' => (int) $attrs['status'],
        ];

        if ($input->existingId !== null) {
            $payload['data_atualizacao'] = $input->now->format('Y-m-d H:i:s');

            return $this->repository->update($input->existingId, $payload, $tenant);
        }

        $payload['cliente_id'] = $input->clienteId;
        $payload['usuario_criador_id'] = $input->userId;
        $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');

        return $this->repository->create($payload);
    }
}
