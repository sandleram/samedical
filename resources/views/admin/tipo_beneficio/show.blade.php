@extends('layouts.admin')

@section('title', 'Tipo de Benefício')

@section('breadcrumb')
    <li><a href="{{ route('admin.tipo_beneficio.index') }}">Tipos de Benefício</a></li>
    <li>#{{ $tipoBeneficio->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-folder-open"></i> Tipo de Benefício #{{ $tipoBeneficio->id }}</h4>
            <dl class="dl-horizontal">
                <dt>ID</dt><dd>{{ $tipoBeneficio->id }}</dd>
                    <dt>Descrição</dt><dd>{{ $tipoBeneficio->descricao ?? '-' }}</dd>
                    <dt>Data de Cancelamento</dt><dd>{{ optional($tipoBeneficio->data_cancelamento)->format('d/m/Y') ?? '-' }}</dd>
                    <dt>Status</dt><dd>{{ (int) $tipoBeneficio->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
                <dt>Data Cadastro</dt><dd>{{ optional($tipoBeneficio->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</dd>
            </dl>
            <p style="margin-top:15px;">
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.tipo_beneficio.add', $tipoBeneficio->id) }}">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.tipo_beneficio.index') }}">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
