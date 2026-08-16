@extends('layouts.admin')

@section('title', $title)

@section('content')
<section id="widget-grid">
    <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"
         data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">
        <header>
            <span class="widget-icon"><i class="fa fa-list"></i></span>
            <h2>{{ $title }}</h2>
        </header>
        <div class="widget-body" style="padding:15px;">
            @if (! empty($downloadDeferred))
                <p class="text-muted"><em>Exportação Excel (download) deferida nesta onda.</em></p>
            @endif
            <form method="GET" class="smart-form client-form" style="margin-bottom:15px;">
                <div class="row">
                    <section class="col col-1">
                        <label class="input"><input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login"></label>
                    </section>
                    <section class="col col-2">
                        <label class="input"><input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login"></label>
                    </section>
                    <section class="col col-2">
                        <label class="input"><input type="text" name="cpf" value="{{ $search['cpf'] ?? '' }}" placeholder="CPF" class="cpf_mask input_login"></label>
                    </section>
                    <section class="col col-1">
                        <button type="submit" class="btn btn-primary" style="padding:4px 10px;">Filtrar</button>
                    </section>
                </div>
            </form>
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>CPF</th>
                    <th>Nome</th>
                    <th>Situação</th>
                    <th>Cliente</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->cpf }}</td>
                        <td>{{ $row->nome }}</td>
                        <td>{{ $row->situacao }}</td>
                        <td>{{ $row->cliente?->nome }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Nenhum registro.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $rows->links('pagination::bootstrap-3') }}
            <p><a href="{{ route('admin.relatorio.index') }}" class="btn btn-default">Voltar</a></p>
        </div>
    </div>
</section>
@endsection
