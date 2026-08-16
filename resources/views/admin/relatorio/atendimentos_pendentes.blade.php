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
                    <th>Data/Hora</th>
                    <th>Responsável</th>
                    <th>Usuário</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($rows as $row)
                    <tr>
                        <td>{{ $row->id }}</td>
                        <td>{{ optional($row->data_hora)->format('d/m/Y H:i') }}</td>
                        <td>{{ $row->usuarioAgendamento?->nome ?? $row->usuario_agendamento_id }}</td>
                        <td>{{ $row->usuario?->nome ?? $row->usuario_id }}</td>
                        <td>{{ $row->status }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center">Nenhum atendimento pendente.</td></tr>
                @endforelse
                </tbody>
            </table>
            {{ $rows->links('pagination::bootstrap-3') }}
            <p><a href="{{ route('admin.relatorio.index') }}" class="btn btn-default">Voltar</a></p>
        </div>
    </div>
</section>
@endsection
