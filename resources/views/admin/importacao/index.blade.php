@extends('layouts.admin')

@section('title', 'Importações')

@section('breadcrumb')
    <li>Importação</li>
    <li>Todos</li>
@endsection

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-importacao"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todas Importações</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form method="GET" action="{{ route('admin.importacao.index') }}"
                          id="importacao-search-form" class="smart-form client-form">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <select name="tipo_importacao" class="input_login">
                                        @foreach ($tipoImportacaoArr as $k => $v)
                                            <option value="{{ $k }}" @selected(($search['tipo_importacao'] ?? '') == $k)>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                            <section class="col col-2">
                                @if (collect($search)->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty())
                                    <a href="{{ route('admin.importacao.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div class="widget-body" style="padding: 0 15px 15px;">
                    <p style="margin-top:10px;">
                        @if ($permissao >= 2)
                            <a class="btn btn-success btn-sm" href="{{ route('admin.importacao.add') }}">
                                <i class="fa fa-plus"></i> Novo
                            </a>
                        @endif
                    </p>

                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            @if (in_array($perfil_id, $perfil_adm, true))
                                <th>Cliente</th>
                            @endif
                            <th>Tipo de Importação</th>
                            <th>Arquivo</th>
                            <th>Data de Cadastro</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                @if (in_array($perfil_id, $perfil_adm, true))
                                    <td>{{ $row->cliente?->nome }}</td>
                                @endif
                                <td>{{ $tipoImportacaoArr[$row->tipo_importacao] ?? $row->tipo_importacao }}</td>
                                <td>{{ $row->arquivo_importado }}</td>
                                <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">Nenhum registro encontrado.</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                    {{ $rows->links('pagination::bootstrap-3') }}
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
