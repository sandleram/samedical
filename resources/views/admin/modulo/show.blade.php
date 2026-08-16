@extends('layouts.admin')

@section('title', 'Módulo')

@section('breadcrumb')
    <li><a href="{{ route('admin.modulo.index') }}">Módulos</a></li>
    <li>{{ $modulo->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de Módulo
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.modulo.add', $modulo->id) }}">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.modulo.index') }}">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $modulo->id }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Módulo Pai: </strong></label>
                        <label>{{ $modulo->parent->nome ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Nome: </strong></label>
                        <label>{{ $modulo->nome }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Controller: </strong></label>
                        <label>{{ $modulo->controller }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Ordem: </strong></label>
                        <label>{{ $modulo->order }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Menu: </strong></label>
                        <label>{{ $modulo->menu }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Ícone: </strong></label>
                        <label>
                            @if ($modulo->icon)
                                <i class="fa {{ $modulo->icon }}"></i> &nbsp; (<i>{{ $modulo->icon }}</i>)
                            @else
                                -
                            @endif
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($modulo->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Atualização: </strong></label>
                        <label>{{ optional($modulo->data_atualizacao)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label>{{ (int) $modulo->status === 1 ? 'Ativo' : ((int) $modulo->status === 2 ? 'Excluído' : 'Inativo') }}</label>
                    </section>
                </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
