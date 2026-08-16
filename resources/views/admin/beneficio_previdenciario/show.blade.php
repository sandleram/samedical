@extends('layouts.admin')
@section('title', 'Benefício Previdenciário')
@section('breadcrumb')
    <li><a href="{{ route('admin.beneficio_previdenciario.index') }}">Benefícios Previdenciários</a></li>
    <li>#{{ $row->id }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-medkit"></i> Benefício Previdenciário #{{ $row->id }}</h4>
            <dl class="dl-horizontal">
                <dt>Beneficiário</dt><dd>{{ $row->beneficiario->nome ?? '-' }}</dd>
                <dt>Empresa</dt><dd>{{ $row->empresa->razao_social ?? '-' }}</dd>
                <dt>Espécie BP</dt><dd>{{ $row->especie_bp_id ?? '-' }}</dd>
                <dt>NB</dt><dd>{{ $row->nb ?? '-' }}</dd>
                <dt>NIT</dt><dd>{{ $row->nit ?? '-' }}</dd>
                <dt>Espécie</dt><dd>{{ $row->especie ?? '-' }}</dd>
                <dt>Situação</dt><dd>{{ $row->situacao ?? '-' }}</dd>
                <dt>Início</dt><dd>{{ optional($row->data_inicio)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Cessação</dt><dd>{{ optional($row->data_cessacao)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Próxima perícia</dt><dd>{{ optional($row->data_proxima_pericia)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Status</dt><dd>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
            </dl>
            <p>
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.beneficio_previdenciario.add', $row->id) }}"><i class="fa fa-edit"></i> Editar</a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.beneficio_previdenciario.index') }}"><i class="fa fa-arrow-left"></i> Voltar</a>
            </p>
        </div>
    </div>
</div>
@endsection
