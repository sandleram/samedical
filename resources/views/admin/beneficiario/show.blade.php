@extends('layouts.admin')

@section('title', 'Beneficiário')

@section('breadcrumb')
    <li><a href="{{ route('admin.beneficiario.index') }}">Beneficiários</a></li>
    <li>{{ $beneficiario->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-3">
                    <div class="well">
                        <h4>
                            <i class="fa fa-user"></i>
                            {{ $firstName }} {{ $lastName }}
                        </h4>
                        <dl class="dl-horizontal" style="margin-bottom:0;">
                            <dt>ID</dt><dd>{{ $beneficiario->id }}</dd>
                            <dt>CPF</dt><dd>{{ $beneficiario->cpf ?? '-' }}</dd>
                            <dt>Matrícula</dt><dd>{{ $beneficiario->cod_matricula ?? '-' }}</dd>
                            <dt>Nascimento</dt>
                            <dd>{{ $beneficiario->data_nascimento?->format('d/m/Y') ?? '-' }}</dd>
                            <dt>Sexo</dt><dd>{{ $beneficiario->sexo ?? '-' }}</dd>
                            <dt>Situação</dt><dd>{{ $beneficiario->situacao ?? '-' }}</dd>
                            <dt>Altura</dt><dd>{{ $alturaFmt }}</dd>
                            <dt>Peso</dt><dd>{{ $pesoFmt }}</dd>
                            <dt>IMC</dt><dd>{{ $beneficiario->imc ?? '-' }}</dd>
                            <dt>Cliente</dt><dd>{{ $beneficiario->cliente->nome ?? '-' }}</dd>
                            <dt>Empresa</dt><dd>{{ $beneficiario->empresa->razao_social ?? $beneficiario->empresa->nome ?? '-' }}</dd>
                            <dt>GE</dt><dd>{{ $beneficiario->cliente->grupoEmpresarial->nome ?? '-' }}</dd>
                        </dl>
                        <p style="margin-top:15px;">
                            @if ($permissao >= 2)
                                <a class="btn btn-primary btn-sm" href="{{ route('admin.beneficiario.add', $beneficiario->id) }}">
                                    <i class="fa fa-edit"></i> Editar
                                </a>
                            @endif
                            <a class="btn btn-default btn-sm" href="{{ route('admin.beneficiario.index') }}">
                                <i class="fa fa-arrow-left"></i> Voltar
                            </a>
                        </p>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                    <div class="widget-body">
                        <ul id="myTab1" class="nav nav-tabs bordered">
                            <li class="active"><a href="#s5" data-toggle="tab">Cadastro</a></li>
                            @if (($permissoes['atendimento']['permissao'] ?? 0) > 0)
                                <li><a href="#s1" data-toggle="tab">Timeline <span class="badge bg-color-blue txt-color-white">0</span></a></li>
                            @endif
                            @if (($permissoes['afastado']['permissao'] ?? 0) > 0)
                                <li><a href="#s2" data-toggle="tab">Afastado <span class="badge bg-color-blue txt-color-white">0</span></a></li>
                            @endif
                            @if (($permissoes['beneficio_previdenciario']['permissao'] ?? 0) > 0)
                                <li><a href="#s3" data-toggle="tab">Benefício Previdenciário <span class="badge bg-color-blue txt-color-white">0</span></a></li>
                            @endif
                            @if (($permissoes['absenteismo']['permissao'] ?? 0) > 0)
                                <li><a href="#s4" data-toggle="tab">Absenteísmo <span class="badge bg-color-blue txt-color-white">0</span></a></li>
                            @endif
                        </ul>

                        <div id="myTabContent1" class="tab-content padding-10">
                            <div class="tab-pane fade in active" id="s5">
                                <dl class="dl-horizontal">
                                    <dt>Email</dt><dd>{{ $beneficiario->email ?? '-' }}</dd>
                                    <dt>RG</dt><dd>{{ $beneficiario->rg ?? '-' }}</dd>
                                    <dt>PIS</dt><dd>{{ $beneficiario->pis ?? '-' }}</dd>
                                    <dt>Estado Civil</dt><dd>{{ $beneficiario->estado_civil ?? '-' }}</dd>
                                    <dt>Telefone</dt>
                                    <dd>{{ $beneficiario->telefone_tipo }} {{ $beneficiario->telefone }}</dd>
                                    <dt>Endereço</dt>
                                    <dd>
                                        {{ $beneficiario->endereco }}
                                        {{ $beneficiario->bairro }}
                                        {{ $beneficiario->cidade }}/{{ $beneficiario->estado }}
                                        {{ $beneficiario->cep }}
                                    </dd>
                                    <dt>Profissão</dt><dd>{{ $beneficiario->profissao ?? '-' }}</dd>
                                    <dt>Ocupação</dt><dd>{{ $beneficiario->ocupacao ?? '-' }}</dd>
                                    <dt>Benefício</dt><dd>{{ $beneficiario->beneficio ?? '-' }}</dd>
                                    <dt>Observação</dt><dd>{{ $beneficiario->observacao ?? '-' }}</dd>
                                    <dt>Status</dt>
                                    <dd>{{ (int) $beneficiario->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
                                </dl>
                            </div>
                            <div class="tab-pane fade" id="s1">
                                <p class="text-muted">Timeline / atendimentos — backlog (Onda C).</p>
                            </div>
                            <div class="tab-pane fade" id="s2">
                                <p class="text-muted">Afastados — backlog (Onda C).</p>
                            </div>
                            <div class="tab-pane fade" id="s3">
                                <p class="text-muted">Benefício previdenciário — backlog (Onda C).</p>
                            </div>
                            <div class="tab-pane fade" id="s4">
                                <p class="text-muted">Absenteísmo — backlog (Onda C).</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
