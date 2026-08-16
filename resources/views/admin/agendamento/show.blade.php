@extends('layouts.admin')
@section('title', 'Agendamento')
@section('breadcrumb')
    <li><a href="{{ route('admin.agendamento.index') }}">Agendamentos</a></li>
    <li>#{{ $row->id }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-calendar-check-o"></i> Agendamento #{{ $row->id }}</h4>
            <dl class="dl-horizontal">
                <dt>Atendimento</dt><dd>#{{ $row->atendimento_id }} — {{ $row->atendimento->beneficiario->nome ?? '-' }}</dd>
                <dt>Usuário</dt><dd>{{ $row->usuario->nome ?? '-' }}</dd>
                <dt>Agendado para</dt><dd>{{ $row->usuarioAgendamento->nome ?? '-' }}</dd>
                <dt>Data/Hora</dt><dd>{{ optional($row->data_hora)->format('d/m/Y H:i') ?? '-' }}</dd>
                <dt>Descrição</dt><dd>{{ $row->descricao ?? '-' }}</dd>
                <dt>Status</dt><dd>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
            </dl>
            <p>
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.agendamento.add', $row->id) }}"><i class="fa fa-edit"></i> Editar</a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.agendamento.index') }}"><i class="fa fa-arrow-left"></i> Voltar</a>
            </p>
        </div>
    </div>
</div>
@endsection
