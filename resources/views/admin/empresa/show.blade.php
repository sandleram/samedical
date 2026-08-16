@extends('layouts.admin')

@section('title', 'Empresa')

@section('breadcrumb')
    <li><a href="{{ route('admin.empresa.index') }}">Empresas</a></li>
    <li>{{ $row->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de Empresa
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.empresa.add', $row->id) }}">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.empresa.index') }}">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-6">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $row->id }}</label>
                    </section>
                </div>

                <section>
                    <label class="Bold"><strong>Empresa: </strong></label>
                    <label>{{ $row->nome }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Razão Social: </strong></label>
                    <label>{{ $row->razao_social ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Nome Fantasia: </strong></label>
                    <label>{{ $row->nome_fantasia ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>CNPJ: </strong></label>
                    <label>{{ $row->cnpj ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Inscrição Estadual: </strong></label>
                    <label>{{ $row->inscricao_estadual ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Inscrição Municipal: </strong></label>
                    <label>{{ $row->inscricao_municipal ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Número Funcionários: </strong></label>
                    <label>{{ $row->numero_funcionarios ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Descrição: </strong></label>
                    <label>{{ $row->descricao ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Porte: </strong></label>
                    <label>{{ $porteArr[$row->porte] ?? ($row->porte ?: '-') }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Faturamento: </strong></label>
                    <label>{{ $faturamentoArr[$row->faturamento] ?? ($row->faturamento ?: '-') }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Tipo: </strong></label>
                    <label>{{ $tipoArr[$row->tipo] ?? ($row->tipo ?: '-') }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Localização: </strong></label>
                    <label>
                        @if ($row->endereco)
                            {{ $row->endereco }}, {{ $row->numero }} {{ $row->complemento }}
                            - {{ $row->bairro }} - {{ $row->cidade }} - {{ $row->estado }} - {{ $row->cep }}
                        @else
                            -
                        @endif
                    </label>
                </section>
                <section>
                    <label class="Bold"><strong>Telefone: </strong></label>
                    <label>{{ $row->telefone ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Email: </strong></label>
                    <label>{{ $row->email ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Site: </strong></label>
                    <label>{{ $row->site ?? '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Cliente: </strong></label>
                    <label>{{ $row->cliente->nome ?? $row->cliente_id }}</label>
                </section>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Atualização: </strong></label>
                        <label>{{ optional($row->data_atualizacao)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label>{{ (int) $row->status === 1 ? 'Ativo' : ((int) $row->status === 2 ? 'Excluído' : 'Inativo') }}</label>
                    </section>
                </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
