@extends('layouts.admin')

@section('title', 'Relatórios')

@section('breadcrumb')
    <li>Relatorios</li>
@endsection

@section('content')
<div class="widget-body">
    <h2>Relatórios Disponíveis</h2>
    <p>Todos Relatórios</p>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>RELATÓRIO GERAL</th>
                <th width="200">Acesso</th>
            </tr>
            </thead>
            <tbody>
            @if ($isRoot || (int) data_get($permissoes, 'relatorio/beneficiarios.permissao', data_get($permissoes, 'relatorio.permissao', 0)) > 0)
                <tr>
                    <td>Beneficiarios</td>
                    <td><a href="{{ route('admin.relatorio.beneficiarios') }}">Acessar</a></td>
                </tr>
            @endif
            @if ($isRoot || (int) data_get($permissoes, 'relatorio/afastados.permissao', data_get($permissoes, 'relatorio.permissao', 0)) > 0)
                <tr>
                    <td>Afastados</td>
                    <td><a href="{{ route('admin.relatorio.afastados') }}">Acessar</a></td>
                </tr>
            @endif
            @if ($isRoot || (int) data_get($permissoes, 'relatorio/atendimentos_pendentes.permissao', data_get($permissoes, 'relatorio.permissao', 0)) > 0)
                <tr>
                    <td>Atendimentos Pendentes</td>
                    <td><a href="{{ route('admin.relatorio.atendimentos_pendentes') }}">Acessar</a></td>
                </tr>
            @endif
            </tbody>
        </table>

        @if ($isRoot)
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>RELATÓRIOS OPERACIONAIS</th>
                    <th width="200">Acesso</th>
                </tr>
                </thead>
                <tbody>
                <tr><td>Gerencial</td><td><a href="{{ route('admin.relatorio.gerencial') }}">Acessar</a></td></tr>
                <tr><td>Fatura e Sinistro</td><td><a href="{{ route('admin.relatorio.exportacao') }}">Acessar</a></td></tr>
                <tr><td>Movimentação de Beneficiários</td><td><a href="{{ route('admin.relatorio.movimentacao_beneficiario') }}">Acessar</a></td></tr>
                <tr><td>Movimentação de Sinistro</td><td><a href="{{ route('admin.relatorio.movimentacao_sinistro') }}">Acessar</a></td></tr>
                <tr><td>Movimentação de Fatura</td><td><a href="{{ route('admin.relatorio.movimentacao_fatura') }}">Acessar</a></td></tr>
                <tr><td>Fatura</td><td><a href="{{ route('admin.relatorio.fatura') }}">Acessar</a></td></tr>
                <tr><td>Sinistro</td><td><a href="{{ route('admin.relatorio.sinistro') }}">Acessar</a></td></tr>
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection
