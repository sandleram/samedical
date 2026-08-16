@extends('layouts.admin')

@section('title', 'Usuário')

@section('breadcrumb')
    <li><a href="{{ route('admin.usuario.index') }}">Usuários</a></li>
    <li>{{ $usuario->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de Usuário
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.usuario.add', $usuario->id) }}">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.usuario.index') }}">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $usuario->id }}</label>
                    </section>
                    <section class="col col-6">
                        <label class="label" style="text-align: right;">
                            <strong>Criado por:</strong>
                            <i>{{ $usuario->usuarioCriador->nome ?? '-' }}</i>
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label class="Bold"><strong>G.E.: </strong></label>
                        <label>{{ $usuario->grupoEmpresarial->nome ?? '-' }}</label>
                    </section>
                    <section class="col col-3">
                        <label class="Bold"><strong>Perfil: </strong></label>
                        <label>{{ $usuario->perfil->nome ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-3">
                        <label class="Bold"><strong>Apelido: </strong></label>
                        <label>{{ $usuario->apelido }}</label>
                    </section>
                    <section class="col col-3">
                        <label class="Bold"><strong>Nome: </strong></label>
                        <label>{{ $usuario->nome }}</label>
                    </section>
                    <section class="col col-3">
                        <label class="Bold"><strong>Usuário: </strong></label>
                        <label>{{ $usuario->usuario }}</label>
                    </section>
                    <section class="col col-3">
                        <label class="Bold"><strong>Email: </strong></label>
                        <label>{{ $usuario->email }}</label>
                    </section>
                </div>

                <header style="margin-top:20px; margin-bottom: 15px;">DADOS PESSOAIS</header>
                <div class="row">
                    <section class="col col-2">
                        <label class="Bold"><strong>Sexo: </strong></label>
                        <label>{{ $usuario->sexo ?? '-' }}</label>
                    </section>
                    <section class="col col-2">
                        <label class="Bold"><strong>Rg: </strong></label>
                        <label>{{ $usuario->rg ?? '-' }}</label>
                    </section>
                    <section class="col col-2">
                        <label class="Bold"><strong>Cpf: </strong></label>
                        <label>{{ $usuario->cpf ?? '-' }}</label>
                    </section>
                    <section class="col col-2">
                        <label class="Bold"><strong>Data Nascimento: </strong></label>
                        <label>{{ optional($usuario->data_nascimento)->format('d/m/Y') ?? '-' }}</label>
                    </section>
                </div>

                <header style="margin-top:20px; margin-bottom: 15px;">CONTATOS</header>
                <div class="row">
                    <section class="col col-3">
                        <label class="Bold"><strong>Telefone 1: </strong></label>
                        <label>{{ $usuario->tel1_tipo }}: {{ $usuario->tel1 }}</label>
                    </section>
                    <section class="col col-3">
                        <label class="Bold"><strong>Telefone 2: </strong></label>
                        <label>{{ $usuario->tel2_tipo }}: {{ $usuario->tel2 }}</label>
                    </section>
                    <section class="col col-3">
                        <label class="Bold"><strong>Telefone 3: </strong></label>
                        <label>{{ $usuario->tel3_tipo }}: {{ $usuario->tel3 }}</label>
                    </section>
                </div>

                <div class="row" style="margin-top:30px;">
                    <section class="col col-12">
                        <label class="Bold label"><strong>Observações: </strong></label>
                        <label>{!! $usuario->observacao !!}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($usuario->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Atualização: </strong></label>
                        <label>{{ optional($usuario->data_atualizacao)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label>{{ (int) $usuario->status === 1 ? 'Ativo' : ((int) $usuario->status === 2 ? 'Excluído' : 'Inativo') }}</label>
                    </section>
                </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
