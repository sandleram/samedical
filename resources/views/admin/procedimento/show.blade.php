@extends('layouts.admin')

@section('title', 'Procedimento')

@section('breadcrumb')
    <li><a href="{{ route('admin.procedimento.index') }}">Procedimentos</a></li>
    <li>#{{ $procedimento->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-folder-open"></i> Procedimento #{{ $procedimento->id }}</h4>
            <dl class="dl-horizontal">
                <dt>ID</dt><dd>{{ $procedimento->id }}</dd>
                    <dt>Código</dt><dd>{{ $procedimento->cod_procedimento ?? '-' }}</dd>
                    <dt>Descrição</dt><dd>{{ $procedimento->ds_procedimento ?? '-' }}</dd>
                    <dt>Tipo</dt><dd>{{ $procedimento->tipo_procedimento ?? '-' }}</dd>
                    <dt>Grupo</dt><dd>{{ $procedimento->Grupo ?? '-' }}</dd>
                    <dt>Subgrupo</dt><dd>{{ $procedimento->Subgrupo ?? '-' }}</dd>
                    <dt>Status</dt><dd>{{ (int) $procedimento->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
                <dt>Data Cadastro</dt><dd>{{ optional($procedimento->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</dd>
            </dl>
            <p style="margin-top:15px;">
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.procedimento.add', $procedimento->id) }}">
                        <i class="fa fa-edit"></i> Editar
                    </a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.procedimento.index') }}">
                    <i class="fa fa-arrow-left"></i> Voltar
                </a>
            </p>
        </div>
    </div>
</div>
@endsection
