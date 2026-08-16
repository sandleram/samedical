@extends('layouts.admin')

@section('title', 'BI')

@section('breadcrumb')
    <li>BI</li>
    <li>Cadastro</li>
@endsection

@section('content')
<section id="widget-grid">
    <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"
         data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">
        <header>
            <span class="widget-icon"><i class="fa fa-list"></i></span>
            <h2>Todos BI</h2>
        </header>
        <div class="row" style="padding:10px 15px 0;">
            <form method="GET" action="{{ route('admin.bi.index') }}" class="smart-form client-form" id="bi-search-form">
                <div class="row">
                    <section class="col col-1">
                        <label class="input">
                            <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                        </label>
                    </section>
                    <section class="col col-2">
                        <label class="input">
                            <input type="text" name="titulo" value="{{ $search['titulo'] ?? '' }}" placeholder="Título" class="input_login">
                        </label>
                    </section>
                    <section class="col col-1">
                        <label class="select">
                            <select name="status" class="input_login">
                                <option value="">Status...</option>
                                <option value="1" @selected((string)($search['status'] ?? '') === '1')>Ativo</option>
                                <option value="0" @selected((string)($search['status'] ?? '') === '0')>Inativo</option>
                            </select>
                            <i></i>
                        </label>
                    </section>
                    <section class="col col-1">
                        <button type="submit" class="btn btn-primary" style="padding:4px 10px;">Filtrar</button>
                    </section>
                </div>
            </form>
        </div>
        <div class="widget-body" style="padding:0 15px 15px;">
            <p>
                @if ($permissao >= 2)
                    <a class="btn btn-success btn-sm" href="{{ route('admin.bi.add') }}"><i class="fa fa-plus"></i> Novo</a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.bi.lista') }}">Lista Dashboards</a>
            </p>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Subtítulo</th>
                    <th>Ordem</th>
                    <th>Status</th>
                    <th width="100">Ações</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->titulo }}</td>
                        <td>{{ $row->subtitulo }}</td>
                        <td>{{ $row->ordem }}</td>
                        <td>{{ (int)$row->status === 1 ? 'Ativo' : 'Inativo' }}</td>
                        <td>
                            <a class="btn btn-xs btn-primary" href="{{ route('admin.bi.view', $row->id) }}">Ver</a>
                            @if ($permissao >= 2)
                                <a class="btn btn-xs btn-default" href="{{ route('admin.bi.add', $row->id) }}">Editar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center">Nenhum registro.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $rows->links('pagination::bootstrap-3') }}
        </div>
    </div>
</section>
@endsection
