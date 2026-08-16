@extends('layouts.admin')

@section('title', 'Subfatura')

@section('breadcrumb')
    <li><a href="{{ route('admin.subfatura.index') }}">Subfaturas</a></li>
    <li>#{{ $subfatura->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-folder-open"></i> Subfatura #{{ $subfatura->id }}</h4>
            <dl class="dl-horizontal">
                <dt>ID</dt><dd>{{ $subfatura->id }}</dd>
                    <dt>Benefício</dt><dd>{{ $subfatura->beneficio->descricao ?? '-' }}</dd>
                    <dt>Descrição</dt><dd>{{ $subfatura->descricao ?? '-' }}</dd>
                    <dt>Código</dt><dd>{{ $subfatura->codigo ?? '-' }}</dd>
                    <dt>Data de Cancelamento</dt><dd>{{ optional($subfatura->data_cancelamento)->format('d/m/Y') ?? '-' }}</dd>
                    <dt>Status</dt><dd>{{ (int) $subfatura->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
                <dt>Data Cadastro</dt><dd>{{ optional($subfatura->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</dd>
            </dl>
            <p style="margin-top:15px;">
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.subfatura.add', $subfatura->id) }}">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.subfatura.index') }}">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
