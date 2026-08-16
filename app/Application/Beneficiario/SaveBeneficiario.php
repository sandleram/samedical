<?php

namespace App\Application\Beneficiario;

use App\Domain\Beneficiario\Beneficiario;
use App\Domain\Beneficiario\BeneficiarioRepositoryInterface;
use App\Domain\Beneficiario\ImcCalculator;
use App\Domain\Empresa\EmpresaRepositoryInterface;
use App\Domain\Shared\TenantScope;
use RuntimeException;

final class SaveBeneficiario
{
    public function __construct(
        private readonly BeneficiarioRepositoryInterface $beneficiarios,
        private readonly EmpresaRepositoryInterface $empresas,
    ) {}

    public function execute(SaveBeneficiarioInput $input, TenantScope $tenant): Beneficiario
    {
        $attrs = $input->attributes;
        $empresaId = (int) ($attrs['empresa_id'] ?? 0);

        if (! $this->empresas->belongsToCliente($empresaId, $input->clienteId)) {
            throw new RuntimeException('Empresa inválida para o cliente da sessão.');
        }

        $cpf = str_replace(['.', '-'], '', (string) ($attrs['cpf'] ?? ''));
        $peso = isset($attrs['peso']) && $attrs['peso'] !== '' && $attrs['peso'] !== null
            ? (float) str_replace(',', '.', (string) $attrs['peso'])
            : null;
        $alturaRaw = isset($attrs['altura']) && $attrs['altura'] !== '' && $attrs['altura'] !== null
            ? (float) str_replace(',', '.', (string) $attrs['altura'])
            : null;
        $alturaCm = $alturaRaw !== null ? (int) round($alturaRaw * 100) : null;
        $imc = ImcCalculator::calculate($peso, $alturaRaw);

        $payload = [
            'nome' => $attrs['nome'],
            'nome_social' => $attrs['nome_social'] ?? null,
            'email' => $attrs['email'],
            'empresa_id' => $empresaId,
            'situacao' => $attrs['situacao'] ?? null,
            'beneficio' => $attrs['beneficio'] ?? null,
            'valor_do_seguro' => $this->parseMoney(isset($attrs['valor_do_seguro']) ? (string) $attrs['valor_do_seguro'] : null),
            'cpf' => $cpf,
            'rg' => $attrs['rg'] ?? null,
            'sexo' => $attrs['sexo'],
            'estado_civil' => $attrs['estado_civil'] ?? null,
            'data_nascimento' => $attrs['data_nascimento'],
            'altura' => $alturaCm,
            'peso' => $peso,
            'imc' => $imc,
            'endereco' => $attrs['endereco'] ?? null,
            'bairro' => $attrs['bairro'] ?? null,
            'cidade' => $attrs['cidade'] ?? null,
            'estado' => $attrs['estado'] ?? null,
            'cep' => $attrs['cep'] ?? null,
            'agencia' => $attrs['agencia'] ?? null,
            'conta' => $attrs['conta'] ?? null,
            'tipo_de_conta' => $attrs['tipo_de_conta'] ?? null,
            'profissao' => $attrs['profissao'] ?? null,
            'ocupacao' => $attrs['ocupacao'] ?? null,
            'pessoa_politicamente_exposta' => $attrs['pessoa_politicamente_exposta'] ?? null,
            'realiza_alguma_atividade_perigosa_na_profissao' => $attrs['realiza_alguma_atividade_perigosa_na_profissao'] ?? null,
            'possui_deficiencia' => $attrs['possui_deficiencia'] ?? null,
            'telefone_tipo' => $attrs['telefone_tipo'],
            'telefone' => $attrs['telefone'],
            'telefone1_tipo' => $attrs['telefone1_tipo'] ?? null,
            'telefone1' => $attrs['telefone1'] ?? null,
            'observacao' => $attrs['observacao'] ?? null,
            'cod_matricula' => $attrs['cod_matricula'] ?? null,
            'pis' => $attrs['pis'] ?? null,
        ];

        if ($input->existingId !== null) {
            $payload['usuario_atualizacao_id'] = $input->userId;
            $payload['data_atualizacao'] = $input->now->format('Y-m-d H:i:s');
            if (array_key_exists('status', $attrs) && $attrs['status'] !== null) {
                $payload['status'] = (int) $attrs['status'];
            }

            return $this->beneficiarios->update($input->existingId, $payload, $tenant);
        }

        $payload['cliente_id'] = $input->clienteId;
        $payload['usuario_criador_id'] = $input->userId;
        $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');
        $payload['status'] = 1;

        return $this->beneficiarios->create($payload);
    }

    private function parseMoney(?string $value): ?float
    {
        if ($value === null || trim($value) === '') {
            return null;
        }

        $clean = str_replace(['.', 'R$', ' '], '', $value);
        $clean = str_replace(',', '.', $clean);

        return is_numeric($clean) ? (float) $clean : null;
    }
}
