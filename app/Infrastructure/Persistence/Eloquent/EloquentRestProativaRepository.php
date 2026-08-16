<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Rest\RestProativaRepositoryInterface;
use App\Models\Beneficio;
use App\Models\Cliente;
use App\Models\Cronico;
use App\Models\DwBeneficiario;
use App\Models\Faturamento;
use App\Models\GrupoEstatistico;
use App\Models\Procedimento;
use App\Models\Sinistro;
use App\Models\Subfaturas;

final class EloquentRestProativaRepository implements RestProativaRepositoryInterface
{
    public function beneficiariosByCliente(int $clienteId): array
    {
        $fields = [
            'cliente_id', 'competencia', 'cod_subfatura', 'empresa_id', 'nome', 'cpf', 'estado_civil',
            'data_nascimento', 'cidade', 'estado', 'sexo', 'chave_beneficiario', 'cod_matricula',
            'dt_inclusao', 'dt_exclusao', 'dt_admissao', 'idade', 'faixa_etaria_ans_id', 'ds_grau_parentesco',
            'nome_titular', 'cpf_titular', 'plano_id', 'ds_plano', 'elegibilidade', 'cod_operadora',
            'operadora', 'ds_faixa_etaria_ans', 'ds_tipo_acomodacao', 'cod_u_seg', 'codigo_empresa',
            'grupo_familiar_id', 'relacao_dep', 'relacao_dep_digito', 'lotacao_do_funcionario',
            'cod_empresa', 'num_contrato', 'carteirinha', 'carteirinha_titular',
        ];

        return DwBeneficiario::query()
            ->where('cliente_id', $clienteId)
            ->orderByDesc('id')
            ->get($fields)
            ->map(fn ($row) => $row->toArray())
            ->values()
            ->all();
    }

    public function faturamentosByCliente(int $clienteId): array
    {
        $fields = [
            'cliente_id', 'competencia_referencia', 'competencia', 'codigo_operadora', 'operadora',
            'valor_fatura', 'qtd_vidas', 'reembolso', 'rede', 'coparticipacao', 'revisao', 'recuperacao',
            'valor_sinistro', 'percentual', 'total_sinistro', 'qtd_beneficiarios_atendidos',
        ];

        return Faturamento::query()
            ->where('cliente_id', $clienteId)
            ->orderByDesc('id')
            ->get($fields)
            ->map(fn ($row) => $row->toArray())
            ->values()
            ->all();
    }

    public function sinistrosByCliente(int $clienteId): array
    {
        $fields = [
            'cliente_id', 'empresa_id', 'subfatura_id', 'cod_subfatura', 'chave_beneficiario', 'matricula',
            'cod_grupo_familiar', 'numero_carteira_titular', 'numero_carteira_titular_complemento',
            'cpf_titular', 'nome_titular', 'beneficiario_id', 'numero_carteira', 'numero_carteira_complemento',
            'cpf_beneficiario', 'nome_beneficiario', 'sexo', 'elegibilidade', 'data_nascimento', 'idade',
            'tipo_reembolso', 'cod_prestador', 'nome_prestador', 'cidade_prestador', 'uf_prestador',
            'cod_faixa_etaria_ans', 'plano_id', 'cod_plano', 'ds_plano', 'nro_conta_medica',
            'procedimento_id', 'cod_procedimento', 'ds_procedimento', 'qtde_procedimento', 'tipo_servico',
            'conta_medica', 'valor', 'valor_coparticipacao', 'senha', 'nr_autorizacao', 'ds_especialidade',
            'data_evento', 'data_pagamento', 'cid', 'ds_cid', 'operadora', 'tipo_servico_operadora',
            'tipo_internacao', 'tipo_entrada', 'campo_1_dado', 'campo_2_coluna', 'competencia_robo',
            'ds_parentesco', 'num_contrato', 'nome_contrato', 'apolice', 'codigo_beneficio',
            'data_final_servico', 'co_particiacao_perc', 'tipo_sinistro', 'atendimento_emergencia',
            'tipo_paciente', 'origem_pagamento', 'tabela_grupo', 'codigo_grupo', 'descricao_grupo',
            'codigo_subgrupo', 'descricao_subgrupo', 'data_alta', 'cnpj_prestador', 'nome_hash',
            'nome_prestador_hash',
        ];

        return Sinistro::query()
            ->where('cliente_id', $clienteId)
            ->orderByDesc('id')
            ->get($fields)
            ->map(fn ($row) => $row->toArray())
            ->values()
            ->all();
    }

    public function dump(string $resource): array
    {
        $collection = match ($resource) {
            'beneficio' => Beneficio::query()
                ->whereIn('cliente_id', function ($q) {
                    $q->select('id')->from('cliente')->where('grupo_empresarial_id', 6);
                })
                ->get(['id', 'cliente_id', 'breakeven']),
            'cliente' => Cliente::query()
                ->where('grupo_empresarial_id', 6)
                ->get(['id', 'nome']),
            'grupo_estatistico' => GrupoEstatistico::query()->get(),
            'cronicos' => Cronico::query()->get(),
            'subfaturas' => Subfaturas::query()->get(),
            'procedimento' => Procedimento::query()->get(),
            default => collect(),
        };

        return $collection->map(fn ($row) => $row->toArray())->values()->all();
    }
}
