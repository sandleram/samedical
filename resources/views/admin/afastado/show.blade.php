@extends('layouts.admin')

@section('title', 'Afastado')

@section('breadcrumb')
    <li><a href="{{ route('admin.afastado.index') }}">Afastados</a></li>
    <li>#{{ $afastado->id }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-sm-12">
        <div class="well well-sm">
            <h4><i class="fa fa-user-md"></i> Afastado #{{ $afastado->id }}</h4>
            <dl class="dl-horizontal">
                <dt>Beneficiário</dt><dd>{{ $afastado->beneficiario->nome ?? '-' }}</dd>
                <dt>Empresa</dt><dd>{{ $afastado->empresa->razao_social ?? '-' }}</dd>
                <dt>Situação</dt><dd>{{ $afastado->situacao === 'RT' ? 'Retorno ao Trabalho' : 'Afastado' }}</dd>
                <dt>Início</dt><dd>{{ optional($afastado->data_inicio_afastamento)->format('d/m/Y') ?? '-' }}</dd>
                <dt>Fim</dt><dd>{{ optional($afastado->data_fim_afastamento)->format('d/m/Y') ?? '-' }}</dd>
                <dt>CID</dt><dd>{{ $afastado->cid ?? '-' }}</dd>
                <dt>Tipo</dt><dd>{{ $afastado->tipo_afastamento ?? '-' }}</dd>
                <dt>Ação Trabalhista</dt><dd>{{ $simNaoArr[$afastado->acao_trabalhista] ?? '-' }}</dd>
                <dt>Ação INSS</dt><dd>{{ $simNaoAcaoInssArr[$afastado->acao_inss] ?? '-' }}</dd>
                <dt>Limbo Previdenciário</dt><dd>{{ $simNaoArr[$afastado->limbo_previdenciario] ?? '-' }}</dd>
                <dt>Status</dt><dd>{{ (int) $afastado->status === 1 ? 'Ativo' : 'Inativo' }}</dd>
            </dl>
            <p>
                @if ($permissao >= 2)
                    <a class="btn btn-primary btn-sm" href="{{ route('admin.afastado.add', $afastado->id) }}"><i class="fa fa-edit"></i> Editar</a>
                @endif
                <a class="btn btn-default btn-sm" href="{{ route('admin.afastado.index') }}"><i class="fa fa-arrow-left"></i> Voltar</a>
            </p>
        </div>
    </div>
</div>
@endsection
