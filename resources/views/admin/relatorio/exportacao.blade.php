@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="well no-padding">
    <form method="POST" action="{{ route('admin.relatorio.exportacao') }}" class="smart-form client-form" id="relatorio-form">
        @csrf
        <header>Exportação Sinistro ou Fatura</header>
        <fieldset>
            <div class="alert alert-info">
                Chamada ao serviço externo de Excel está <strong>deferida</strong>. Tela de filtros disponível.
            </div>
            <div class="row">
                <section class="col col-2">
                    <label class="label"><strong>Tipo de Exportação</strong> <span class="campo_obrigatorio">*</span></label>
                    <label class="select">
                        <select name="tipo" class="input_login">
                            @foreach ($tipoExportacaoArr as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <i></i>
                    </label>
                </section>
            </div>
            <div class="row">
                <section class="col col-4">
                    <label class="label"><strong>Beneficio</strong></label>
                    <label class="select">
                        <select name="beneficio_id" class="input_login">
                            @foreach ($beneficioArr as $id => $nome)
                                <option value="{{ $id }}">{{ $nome }}</option>
                            @endforeach
                        </select>
                        <i></i>
                    </label>
                </section>
                <section class="col col-2">
                    <label class="label">Mês</label>
                    <label class="select">
                        <select name="mes" class="input_login">
                            @foreach ($mesArr as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <i></i>
                    </label>
                </section>
                <section class="col col-2">
                    <label class="label">Ano</label>
                    <label class="select">
                        <select name="ano" class="input_login">
                            @foreach ($anoArr as $k => $v)
                                <option value="{{ $k }}">{{ $v }}</option>
                            @endforeach
                        </select>
                        <i></i>
                    </label>
                </section>
            </div>
        </fieldset>
        <footer>
            <button type="submit" class="btn btn-primary">Solicitar (mostra deferimento)</button>
            <a href="{{ route('admin.relatorio.index') }}" class="btn btn-default">Voltar</a>
        </footer>
    </form>
</div>
@endsection
