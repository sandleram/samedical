@extends('layouts.admin')

@section('title', 'Beneficiários')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-12"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todos Beneficiários</h2>
                </header>

                <div class="row">
                    <form id="beneficiario-search-form"
                          method="GET"
                          action="{{ route('admin.beneficiario.index') }}"
                          class="smart-form client-form form_ajax">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Identificador</b>
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="cpf" value="{{ $search['cpf'] ?? '' }}" placeholder="CPF" class="cpf_mask input_login">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o CPF</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome</b>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="nome_social" value="{{ $search['nome_social'] ?? '' }}" placeholder="Nome Social" class="input_login">
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Nome Social</b>
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="select">
                                    <select name="situacao" class="input_login">
                                        <option value="">Situação...</option>
                                        <option value="Ativo" @selected(($search['situacao'] ?? '') === 'Ativo')>Ativo</option>
                                        <option value="Inativo" @selected(($search['situacao'] ?? '') === 'Inativo')>Inativo</option>
                                    </select>
                                    <i></i>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b>
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="select">
                                    <select name="status" class="input_login">
                                        <option value="">Status...</option>
                                        <option value="1" @selected((string) ($search['status'] ?? '') === '1')>Ativo</option>
                                        <option value="0" @selected((string) ($search['status'] ?? '') === '0')>Inativo</option>
                                    </select>
                                    <i></i>
                                    <b class="tooltip tooltip-top-right"><i class="fa fa-chevron-right txt-color-blueLight"></i> Entre com o Status</b>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                            <section class="col col-2">
                                @include('partials.admin.filtro_limpar', ['search' => $search, 'clearUrl' => route('admin.beneficiario.index')])
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="table-responsive">
                        @include('partials.admin.acoes_geral', [
                            'permissao' => $permissao,
                            'addRoute' => route('admin.beneficiario.add'),
                            'novoLabel' => 'Novo Beneficiário',
                            'context' => 'index',
                        ])

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5">ID</th>
                                <th width="10">Timeline</th>
                                <th width="110">CPF</th>
                                <th>Nome</th>
                                <th>Nome Social</th>
                                <th width="150">Data de Cadastro</th>
                                <th>Situação</th>
                                @if (in_array($perfil_id, $perfil_adm, true) || $permissao === 3)
                                    <th width="20">Status</th>
                                    <th class="actions" width="20">Actions</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($beneficiarios as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>
                                        <a href="{{ route('admin.beneficiario.view', $row->id) }}" class="btn btn-primary btn-xs">
                                            <i class="fa fa-user"></i> Acessar
                                        </a>
                                    </td>
                                    <td>{{ \App\Support\Funcoes::formatCpf($row->cpf) }}</td>
                                    <td>{{ $row->nome }}</td>
                                    <td>{{ $row->nome_social }}</td>
                                    <td>{{ $row->data_cadastro?->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>@include('partials.admin.situacao_badge', ['situacao' => $row->situacao])</td>
                                    @if (in_array($perfil_id, $perfil_adm, true) || $permissao === 3)
                                        <td>@include('partials.admin.status_badge', ['status' => $row->status])</td>
                                        <td class="actions">
                                            @include('partials.admin.acoes_lista', [
                                                'permissao' => $permissao,
                                                'viewRoute' => route('admin.beneficiario.view', $row->id),
                                                'editRoute' => route('admin.beneficiario.add', $row->id),
                                            ])
                                        </td>
                                    @endif
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Nenhum registro encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                        <div class="note" style="margin:10px;">* O acesso ao dashboard só terá efeito sobre a empresa clicada se estiver deslogado!</div>
                        {{ $beneficiarios->links() }}
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
