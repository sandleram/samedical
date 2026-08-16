@extends('layouts.admin')

@section('title', 'MH Prestador')

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_prestador.index') }}">MH Prestador</a></li>
    <li>#{{ $row->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de MH Prestador
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.mh_prestador.add', $row->id) }}"><i class="fa fa-edit"></i> Editar</a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.mh_prestador.index') }}"><i class="fa fa-arrow-left"></i> Voltar</a>
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
                    <label class="Bold"><strong>Nome: </strong></label>
                    <label>{{ $row->nome }}</label>
                </section>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>HubSpot: </strong></label>
                        <label>{{ $row->id_hubspot ?: '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Cidade: </strong></label>
                        <label>{{ $row->cidade ?: '-' }}</label>
                    </section>
                    <section class="col col-2">
                        <label class="Bold"><strong>Estado: </strong></label>
                        <label>{{ $row->estado ?: '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Praça: </strong></label>
                        <label>{{ $row->praca ?: '-' }}</label>
                    </section>
                </div>
                <section>
                    <label class="Bold"><strong>Atividade: </strong></label>
                    <label>{{ $row->atividade ?: '-' }}</label>
                </section>
                <section>
                    <label class="Bold"><strong>Descrição: </strong></label>
                    <label>{!! nl2br(e($row->descricao ?: '-')) !!}</label>
                </section>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?: '-' }}</label>
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
