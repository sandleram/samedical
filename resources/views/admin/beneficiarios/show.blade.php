@extends('layouts.admin')

@section('title', 'Beneficiário')

@section('breadcrumb')
    <li><a href="{{ route('admin.beneficiarios.index') }}">Beneficiários</a></li>
    <li>{{ $beneficiario->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <h1 class="page-title txt-color-blueDark">
            <i class="fa fa-user fa-fw"></i> {{ $beneficiario->nome }}
        </h1>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="well">
            <dl class="dl-horizontal">
                <dt>ID</dt>
                <dd>{{ $beneficiario->id }}</dd>
                <dt>CPF</dt>
                <dd>{{ $beneficiario->cpf ?? '-' }}</dd>
                <dt>Matrícula</dt>
                <dd>{{ $beneficiario->matricula ?? '-' }}</dd>
                <dt>Nascimento</dt>
                <dd>{{ $beneficiario->data_nascimento ?? '-' }}</dd>
                <dt>Cliente</dt>
                <dd>{{ $beneficiario->cliente->nome ?? '-' }}</dd>
                <dt>Empresa</dt>
                <dd>{{ $beneficiario->empresa->nome ?? '-' }}</dd>
                <dt>Grupo Empresarial</dt>
                <dd>{{ $beneficiario->grupoEmpresarial->nome ?? '-' }}</dd>
                <dt>Status</dt>
                <dd>{{ (int) $beneficiario->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
            </dl>

            <a href="{{ route('admin.beneficiarios.index') }}" class="btn btn-default">
                <i class="fa fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>
</div>
@endsection
