@extends('layouts.admin')
@section('title', 'Absenteísmo')
@section('breadcrumb')
    <li><a href="{{ route('admin.absenteismo.index') }}">Absenteísmo</a></li>
    <li>#{{ $row->id }}</li>
@endsection
@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-calendar"></i> Absenteísmo #{{ $row->id }}</h4>
            <dl class="dl-horizontal">
                <dt>Beneficiário</dt><dd>{{ $row->beneficiario->nome ?? '-' }}</dd>
                <dt>Empresa</dt><dd>{{ $row->empresa->razao_social ?? '-' }}</dd>
                <dt>CID</dt><dd>{{ $row->cid ?? '-' }}</dd>
                <dt>Saída</dt><dd>{{ optional($row->data_saida)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Retorno</dt><dd>{{ optional($row->data_retorno)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Dias atestado</dt><dd>{{ $row->qtde_dias_atestado ?? '-' }}</dd>
                <dt>Hospital/Clínica</dt><dd>{{ $row->hospital_clinica ?? '-' }}</dd>
                <dt>Profissional</dt><dd>{{ $row->profissional ?? '-' }}</dd>
                <dt>CRM</dt><dd>{{ $row->num_crm ?? '-' }}</dd>
                <dt>Situação</dt><dd>{{ $row->situacao ?? '-' }}</dd>
                <dt>Observação</dt><dd>{{ $row->observacao ?? '-' }}</dd>
                <dt>Status</dt><dd>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
            </dl>
            <p>
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.absenteismo.add', $row->id) }}"><i class="fa fa-edit"></i> Editar</a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.absenteismo.index') }}"><i class="fa fa-arrow-left"></i> Voltar</a>
            </p>
        </div>
    </div>
</div>
@endsection
