@extends('layouts.admin')

@section('title', 'Beneficiário')

@section('breadcrumb')
    <li><a href="{{ route('admin.beneficiario.index') }}">Beneficiários</a></li>
    <li>View</li>
@endsection

@section('content')
@php
    $permAt = (int) ($permissoes['atendimento']['permissao'] ?? 0);
    $permAf = (int) ($permissoes['afastado']['permissao'] ?? 0);
    $permBp = (int) ($permissoes['beneficio_previdenciario']['permissao'] ?? 0);
    $permAb = (int) ($permissoes['absenteismo']['permissao'] ?? 0);
    $permAg = (int) ($permissoes['agendamento']['permissao'] ?? 0);
    $timeline = $related['atendimentos'] ?? [];
    $afastados = $related['afastados'] ?? [];
    $bps = $related['beneficiosPrevidenciarios'] ?? [];
    $abs = $related['absenteismos'] ?? [];
    $avatar = ($beneficiario->sexo === 'Masculino') ? 'male.png' : 'female.png';
    $idade = \App\Support\Funcoes::idade($beneficiario->data_nascimento);
    $firstTab = 's5';
    if ($permAt > 0) { $firstTab = 's1'; }
    elseif ($permAf > 0) { $firstTab = 's2'; }
    elseif ($permBp > 0) { $firstTab = 's3'; }
    elseif ($permAb > 0) { $firstTab = 's4'; }
@endphp
<style>
    .smart-timeline-list li { padding: 10px 0 !important; }
</style>
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <div class="row">
                <div class="col-sm-12 col-md-12 col-lg-3">
                    <div class="well well-light well-sm no-margin no-padding">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="air air-top-left padding-10">
                                    <h4 class="txt-color-black font-md">
                                        @if ($beneficiario->data_nascimento)
                                            {{ $beneficiario->data_nascimento->format('M d, Y') }}
                                            <span class="note" style="color:black">({{ $idade }} anos)</span>
                                        @endif
                                    </h4>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="row">
                                    <div class="col-sm-4 profile-pic">
                                        <img src="{{ asset('img/avatars/'.$avatar) }}" width="80" height="80" alt="">
                                    </div>
                                    <div class="col-sm-8">
                                        <h1>{{ $firstName }}<br><small style="margin-left:20px;">{{ $lastName }}</small></h1>
                                    </div>
                                    <div class="col-sm-12" style="padding: 10px 15px 0 30px;">
                                        <p class="text-muted">
                                            <b>Nome Social:</b>
                                            @if ($beneficiario->nome_social)
                                                <span style="color:red;">{{ $beneficiario->nome_social }}</span>
                                            @else
                                                Não preenchido!
                                            @endif
                                        </p>
                                        <p class="text-muted"><b>CPF:</b> {{ \App\Support\Funcoes::formatCpf($beneficiario->cpf) }}</p>
                                        <p class="text-muted"><b>PIS:</b> {{ $beneficiario->pis ?? '-' }}</p>
                                        <p class="text-muted"><b>Cliente:</b> {{ $beneficiario->cliente->nome ?? '-' }}</p>
                                        <p class="text-muted">
                                            <b>Empresa:</b>
                                            <ul class="note" style="margin-left:20px;">
                                                <li><b>Nome:</b> {{ $beneficiario->empresa->nome ?? '-' }}</li>
                                                <li><b>CNPJ:</b> {{ \App\Support\Funcoes::formatCnpj($beneficiario->empresa->cnpj ?? '') }}</li>
                                            </ul>
                                        </p>
                                        <h5 style="border-bottom: 1px dotted #d3d3d3;">Observação</h5>
                                        <p class="text-muted">{{ $beneficiario->observacao ?: '-' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9">
                    <div class="widget-body">
                        <ul id="myTab1" class="nav nav-tabs bordered">
                            @if ($permAt > 0)
                                <li class="{{ $firstTab === 's1' ? 'active' : '' }}">
                                    <a href="#s1" data-toggle="tab">Timeline <span class="badge bg-color-blue txt-color-white">{{ count($timeline) }}</span></a>
                                </li>
                            @endif
                            @if ($permAf > 0)
                                <li class="{{ $firstTab === 's2' ? 'active' : '' }}">
                                    <a href="#s2" data-toggle="tab">Afastado <span class="badge bg-color-blue txt-color-white">{{ count($afastados) }}</span></a>
                                </li>
                            @endif
                            @if ($permBp > 0)
                                <li class="{{ $firstTab === 's3' ? 'active' : '' }}">
                                    <a href="#s3" data-toggle="tab">Benefício Previdenciário <span class="badge bg-color-blue txt-color-white">{{ count($bps) }}</span></a>
                                </li>
                            @endif
                            @if ($permAb > 0)
                                <li class="{{ $firstTab === 's4' ? 'active' : '' }}">
                                    <a href="#s4" data-toggle="tab">Absenteísmo <span class="badge bg-color-blue txt-color-white">{{ count($abs) }}</span></a>
                                </li>
                            @endif
                            <li class="{{ $firstTab === 's5' ? 'active' : '' }}"><a href="#s5" data-toggle="tab">Cadastro</a></li>
                            <li class="dropdown">
                                <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">Ações <b class="caret"></b></a>
                                <ul class="dropdown-menu dropdown-menu-right">
                                    @if ($permAt > 1)
                                        <li><a href="{{ route('admin.atendimento.add') }}">Novo Atendimento</a></li>
                                    @endif
                                    @if ($permAg > 1)
                                        <li><a href="{{ route('admin.agendamento.add') }}">Novo Agendamento</a></li>
                                    @endif
                                    @if ($permAf > 1)
                                        <li><a href="{{ route('admin.afastado.add') }}">Novo Afastado</a></li>
                                    @endif
                                    @if ($permBp > 1)
                                        <li><a href="{{ route('admin.beneficio_previdenciario.add') }}">Novo Benefício Previdenciário</a></li>
                                    @endif
                                    @if ($permAb > 1)
                                        <li><a href="{{ route('admin.absenteismo.add') }}">Novo Absenteísmo</a></li>
                                    @endif
                                    @if ($permissao > 0)
                                        <li class="divider"></li>
                                        @if ($permissao > 1)
                                            <li><a href="{{ route('admin.beneficiario.add') }}">Novo Beneficiario</a></li>
                                            <li><a href="{{ route('admin.beneficiario.add', $beneficiario->id) }}">Editar Beneficiario</a></li>
                                        @endif
                                        <li><a href="{{ route('admin.beneficiario.index') }}">Lista de Beneficiario</a></li>
                                    @endif
                                </ul>
                            </li>
                        </ul>

                        <div id="myTabContent1" class="tab-content padding-10">
                            @if ($permAt > 0)
                                <div class="tab-pane fade {{ $firstTab === 's1' ? 'in active' : '' }}" id="s1">
                                    <div class="well well-sm" style="margin:-10px; border:none;">
                                        <h4 style="margin-top:0; margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;">Histórico de Atendimentos</h4>
                                        @if (count($timeline) > 0)
                                            <div class="smart-timeline">
                                                <ul class="smart-timeline-list" style="margin-top:10px;">
                                                    @foreach ($timeline as $item)
                                                        <li>
                                                            <div class="smart-timeline-icon">
                                                                <img src="{{ asset('img/avatars/'.$avatar) }}" width="32" height="32" alt="">
                                                            </div>
                                                            <div class="smart-timeline-time"><small>{{ $item['data_cadastro'] ?? '-' }}</small></div>
                                                            <div class="smart-timeline-content">
                                                                <p><a href="{{ route('admin.atendimento.view', $item['id']) }}">#{{ $item['id'] }}</a>
                                                                    @if ($item['cid']) — CID {{ $item['cid'] }} @endif
                                                                </p>
                                                                <p class="text-muted">{{ \Illuminate\Support\Str::limit(strip_tags((string) $item['descricao']), 180) }}</p>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @else
                                            <p class="text-muted">Nenhum atendimento.</p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if ($permAf > 0)
                                <div class="tab-pane fade {{ $firstTab === 's2' ? 'in active' : '' }}" id="s2">
                                    <h4 style="margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;">Lista de afastamentos</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th width="100">#ID</th>
                                                <th>Situação</th>
                                                <th>Data Início</th>
                                                <th>Data Fim</th>
                                                <th>CID</th>
                                                <th>Tipo</th>
                                                <th>Ações</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse ($afastados as $af)
                                                <tr>
                                                    <td>{{ $af['id'] }} @if($af['importacao_id'])(#{{ $af['importacao_id'] }})@else(Manual)@endif</td>
                                                    <td>{{ $af['situacao'] === 'RT' ? 'Retorno ao Trabalho' : ($af['situacao'] === 'A' ? 'Afastado' : '-') }}</td>
                                                    <td>{{ $af['data_inicio_afastamento'] ?? '-' }}</td>
                                                    <td>{{ $af['data_fim_afastamento'] ?? '-' }}</td>
                                                    <td>{{ $af['cid'] ?? '-' }}</td>
                                                    <td>{{ $af['tipo_afastamento'] ?? '-' }}</td>
                                                    <td><a href="{{ route('admin.afastado.view', $af['id']) }}" class="btn btn-xs btn-default">View</a></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="7" class="text-center">Nenhum registro.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if ($permBp > 0)
                                <div class="tab-pane fade {{ $firstTab === 's3' ? 'in active' : '' }}" id="s3">
                                    <h4 style="margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;">Benefício previdenciário</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr><th>ID</th><th>NB</th><th>Espécie</th><th>Situação</th><th>Início</th><th>Ações</th></tr>
                                            </thead>
                                            <tbody>
                                            @forelse ($bps as $bp)
                                                <tr>
                                                    <td>{{ $bp['id'] }}</td>
                                                    <td>{{ $bp['nb'] ?? '-' }}</td>
                                                    <td>{{ $bp['especie'] ?? '-' }}</td>
                                                    <td>{{ $bp['situacao'] ?? '-' }}</td>
                                                    <td>{{ $bp['data_inicio'] ?? '-' }}</td>
                                                    <td><a href="{{ route('admin.beneficio_previdenciario.view', $bp['id']) }}" class="btn btn-xs btn-default">View</a></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center">Nenhum registro.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            @if ($permAb > 0)
                                <div class="tab-pane fade {{ $firstTab === 's4' ? 'in active' : '' }}" id="s4">
                                    <h4 style="margin-bottom:20px; border-bottom: 1px dotted #d3d3d3;">Absenteísmo</h4>
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr><th>ID</th><th>CID</th><th>Saída</th><th>Retorno</th><th>Dias</th><th>Ações</th></tr>
                                            </thead>
                                            <tbody>
                                            @forelse ($abs as $ab)
                                                <tr>
                                                    <td>{{ $ab['id'] }}</td>
                                                    <td>{{ $ab['cid'] ?? '-' }}</td>
                                                    <td>{{ $ab['data_saida'] ?? '-' }}</td>
                                                    <td>{{ $ab['data_retorno'] ?? '-' }}</td>
                                                    <td>{{ $ab['qtde_dias_atestado'] ?? '-' }}</td>
                                                    <td><a href="{{ route('admin.absenteismo.view', $ab['id']) }}" class="btn btn-xs btn-default">View</a></td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="6" class="text-center">Nenhum registro.</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            <div class="tab-pane fade {{ $firstTab === 's5' ? 'in active' : '' }}" id="s5">
                                <dl class="dl-horizontal">
                                    <dt>Email</dt><dd>{{ $beneficiario->email ?? '-' }}</dd>
                                    <dt>RG</dt><dd>{{ $beneficiario->rg ?? '-' }}</dd>
                                    <dt>Matrícula</dt><dd>{{ $beneficiario->cod_matricula ?? '-' }}</dd>
                                    <dt>Sexo</dt><dd>{{ $beneficiario->sexo ?? '-' }}</dd>
                                    <dt>Estado Civil</dt><dd>{{ $beneficiario->estado_civil ?? '-' }}</dd>
                                    <dt>Altura</dt><dd>{{ $alturaFmt }}</dd>
                                    <dt>Peso</dt><dd>{{ $pesoFmt }}</dd>
                                    <dt>IMC</dt><dd>{{ $beneficiario->imc ?? '-' }}</dd>
                                    <dt>Telefone</dt><dd>{{ $beneficiario->telefone_tipo }} {{ $beneficiario->telefone }}</dd>
                                    <dt>Endereço</dt>
                                    <dd>{{ $beneficiario->endereco }} {{ $beneficiario->bairro }} {{ $beneficiario->cidade }}/{{ $beneficiario->estado }} {{ $beneficiario->cep }}</dd>
                                    <dt>Profissão</dt><dd>{{ $beneficiario->profissao ?? '-' }}</dd>
                                    <dt>Ocupação</dt><dd>{{ $beneficiario->ocupacao ?? '-' }}</dd>
                                    <dt>Benefício</dt><dd>{{ $beneficiario->beneficio ?? '-' }}</dd>
                                    <dt>Status</dt><dd>@include('partials.admin.status_badge', ['status' => $beneficiario->status])</dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
