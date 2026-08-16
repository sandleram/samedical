@extends('layouts.admin')

@section('title', 'Perfil')

@section('breadcrumb')
    <li><a href="{{ route('admin.perfil.index') }}">Perfis</a></li>
    <li>{{ $perfil->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de Perfil
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.perfil.add', $perfil->id) }}">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.perfil.index') }}">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $perfil->id }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Nome: </strong></label>
                        <label>{{ $perfil->nome }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Tipo: </strong></label>
                        <label>{{ $tipoLabels[(int) $perfil->tipo] ?? $perfil->tipo }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($perfil->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Atualização: </strong></label>
                        <label>{{ optional($perfil->data_atualizacao)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label>{{ (int) $perfil->status === 1 ? 'Ativo' : ((int) $perfil->status === 2 ? 'Excluído' : 'Inativo') }}</label>
                    </section>
                </div>
            </fieldset>

            @php
                $pmMap = [];
                foreach ($perfil->perfilModulos as $pm) {
                    $pmMap[(int) $pm->modulo_id] = (int) $pm->permissao;
                }
                $permLabels = [0 => 'Sem Acesso', 1 => 'Visualizar', 2 => 'Adicionar/Editar', 3 => 'Gerenciar'];
            @endphp

            <fieldset>
                <header><h3>Gerenciamento de Acessos</h3></header>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>Módulo</th>
                        <th width="140">Permissão</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($modulos->where('modulo_id', 0) as $pai)
                        <tr style="background-color:#aaa; color:#fff;">
                            <td>{{ $pai->nome }}</td>
                            <td>{{ $permLabels[$pmMap[(int) $pai->id] ?? 0] ?? '-' }}</td>
                        </tr>
                        @foreach ($modulos->where('modulo_id', $pai->id) as $filho)
                            <tr>
                                <td>&nbsp;&nbsp;<span style="font-size:16px;">&bull;</span> {{ $filho->nome }}</td>
                                <td>{{ $permLabels[$pmMap[(int) $filho->id] ?? 0] ?? '-' }}</td>
                            </tr>
                            @foreach ($modulos->where('modulo_id', $filho->id) as $neto)
                                <tr>
                                    <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:14px;">&ordm;</span> {{ $neto->nome }}</td>
                                    <td>{{ $permLabels[$pmMap[(int) $neto->id] ?? 0] ?? '-' }}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                    </tbody>
                </table>
            </fieldset>
        </div>
    </div>
</div>
@endsection
