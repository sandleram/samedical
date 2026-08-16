@extends('layouts.admin')

@section('title', 'Benefício')

@section('breadcrumb')
    <li><a href="{{ route('admin.beneficio.index') }}">Benefícios</a></li>
    <li>#{{ $beneficio->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-folder-open"></i> Benefício #{{ $beneficio->id }}</h4>
            <dl class="dl-horizontal">
                <dt>ID</dt><dd>{{ $beneficio->id }}</dd>
                    <dt>Descrição</dt><dd>{{ $beneficio->descricao ?? '-' }}</dd>
                    <dt>Breakeven</dt><dd>{{ $beneficio->breakeven ?? '-' }}</dd>
                    <dt>Contrato</dt><dd>{{ $beneficio->contrato ?? '-' }}</dd>
                    <dt>Operadora</dt><dd>{{ $beneficio->operadora->nome ?? '-' }}</dd>
                    <dt>Tipo de Benefício</dt><dd>{{ $beneficio->tipoBeneficio->descricao ?? '-' }}</dd>
                    <dt>Data de Cancelamento</dt><dd>{{ optional($beneficio->data_cancelamento)->format('d/m/Y') ?? '-' }}</dd>
                    <dt>Status</dt><dd>{{ (int) $beneficio->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
                <dt>Data Cadastro</dt><dd>{{ optional($beneficio->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</dd>
            </dl>
            <p style="margin-top:15px;">
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.beneficio.add', $beneficio->id) }}">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.beneficio.index') }}">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
