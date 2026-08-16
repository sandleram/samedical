@extends('layouts.admin')

@section('title', 'Importação Nova')

@section('breadcrumb')
    <li>Importação Nova</li>
    <li>Todos</li>
@endsection

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-importacao-nova"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todas Importações Novas</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form method="GET" action="{{ route('admin.importacao_nova.index') }}"
                          id="importacao_nova-search-form" class="smart-form client-form">
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
                            <section class="col col-2">
                                <label class="select">
                                    <select name="status_processo" class="input_login">
                                        <option value="">Processo...</option>
                                        @foreach ($statusProcessoArr as $k => $v)
                                            <option value="{{ $k }}" @selected((string) ($search['status_processo'] ?? '') === (string) $k)>{{ $v }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                        </div>
                    </form>
                </div>

                <div class="widget-body" style="padding: 0 15px 15px;">
                    <p style="margin-top:10px;">
                        @if ($permissao >= 2)
                            <a class="btn btn-success btn-sm" href="{{ route('admin.importacao_nova.add') }}">
                                <i class="fa fa-plus"></i> Novo
                            </a>
                        @endif
                    </p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Arquivo</th>
                            <th>Tipo</th>
                            <th>Processo</th>
                            <th>Linhas</th>
                            <th>Cadastro</th>
                            <th width="80">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($rows as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->nome_arquivo }}</td>
                                <td>{{ $tipoImportacaoArr[$row->tipo_importacao] ?? $row->tipo_importacao }}</td>
                                <td>{{ $statusProcessoArr[$row->status_processo] ?? $row->status_processo }}</td>
                                <td>{{ $row->linhas_processadas }} / {{ $row->linhas_totais }}</td>
                                <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a class="btn btn-primary btn-xs" href="{{ route('admin.importacao_nova.view', $row->id) }}">Ver</a>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Nenhum registro encontrado.</td></tr>
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
