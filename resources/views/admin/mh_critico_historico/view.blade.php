@extends('layouts.admin')

@section('title', 'MH Crítico Histórico')

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_critico.index') }}">MH Crítico</a></li>
    <li><a href="{{ route('admin.mh_critico_historico.index', $mh_critico_id) }}">Histórico</a></li>
    <li>#{{ $row->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de MH Crítico Histórico
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.mh_critico_historico.add', [$mh_critico_id, $row->id]) }}"><i class="fa fa-edit"></i> Editar</a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.mh_critico_historico.index', $mh_critico_id) }}"><i class="fa fa-arrow-left"></i> Voltar</a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-6">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $row->id }}</label>
                    </section>
                </div>
                <section>
                    <label class="Bold"><strong>Descrição: </strong></label>
                    <label>{!! nl2br(e($row->descricao)) !!}</label>
                </section>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Ciclo: </strong></label>
                        <label>{{ $ArrCiclo[$row->ciclo] ?? $row->ciclo }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status Ciclo: </strong></label>
                        <label>{{ $ArrStatusCiclo[$row->status_ciclo] ?? $row->status_ciclo }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($row->data_cadastro)->format('d/m/Y H:i') }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</label>
                    </section>
                </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
