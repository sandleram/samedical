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
            <table class="table table-bordered table-striped">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Beneficiário</th>
                    <th>CPF</th>
                    <th>Cliente</th>
                    <th>Início</th>
                    <th>Fim</th>
                    <th>CID</th>
                    <th>Situação</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ $row->beneficiario?->nome }}</td>
                        <td>{{ $row->beneficiario?->cpf }}</td>
                        <td>{{ $row->beneficiario?->cliente?->nome }}</td>
                        <td>{{ optional($row->data_inicio_afastamento)->format('d/m/Y') }}</td>
                        <td>{{ optional($row->data_fim_afastamento)->format('d/m/Y') }}</td>
                        <td>{{ $row->cid }}</td>
                        <td>{{ $row->situacao === 'A' ? 'Afastado' : $row->situacao }}</td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center">Nenhum registro.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $rows->links('pagination::bootstrap-3') }}
            <p><a href="{{ route('admin.relatorio.index') }}" class="btn btn-default">Voltar</a></p>
        </div>
    </div>
</section>
@endsection
