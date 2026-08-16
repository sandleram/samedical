@extends('layouts.admin')
@section('title', 'Atendimento')
@section('breadcrumb')
    <li><a href="{{ route('admin.atendimento.index') }}">Atendimentos</a></li>
    <li>#{{ $row->id }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-stethoscope"></i> Atendimento #{{ $row->id }}</h4>
            <dl class="dl-horizontal">
                <dt>Beneficiário</dt><dd>{{ $row->beneficiario->nome ?? '-' }}</dd>
                <dt>Usuário</dt><dd>{{ $row->usuario->nome ?? '-' }}</dd>
                <dt>Tipo</dt><dd>{{ ($tipoAtendimentoArr[$row->tipo_atendimento] ?? $row->tipo_atendimento) ?: '-' }}</dd>
                <dt>CID</dt><dd>{{ $row->cid ?? '-' }}</dd>
                <dt>Descrição</dt><dd>{{ $row->descricao ?? '-' }}</dd>
                <dt>Forma</dt><dd>{{ $row->forma_atendimento ?? '-' }}</dd>
                <dt>Status atendimento</dt><dd>{{ $row->status_atendimento ?? '-' }}</dd>
                <dt>Conclusão</dt><dd>{{ optional($row->data_conclusao)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Cadastro</dt><dd>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</dd>
                <dt>Status</dt><dd>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
            </dl>
            <p>
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.atendimento.add', $row->id) }}"><i class="fa fa-edit"></i> Editar</a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.atendimento.index') }}"><i class="fa fa-arrow-left"></i> Voltar</a>
            </p>
        </div>
    </div>
</div>
@endsection
