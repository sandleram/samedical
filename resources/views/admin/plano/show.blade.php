@extends('layouts.admin')

@section('title', 'Plano')

@section('breadcrumb')
    <li><a href="{{ route('admin.plano.index') }}">Planos</a></li>
    <li>{{ $row->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de Plano
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.plano.add', $row->id) }}">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.plano.index') }}">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $row->id }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Nome: </strong></label>
                        <label>{{ $row->nome }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Código Operadora: </strong></label>
                        <label>{{ $row->codigo_operadora }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Operadora: </strong></label>
                        <label>{{ $row->operadora->nome ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Tipo Beneficio: </strong></label>
                        <label>{{ $row->tipoBeneficio->descricao ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Ordenação: </strong></label>
                        <label>{{ $row->ordem ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</label>
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
