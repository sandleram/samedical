@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="well no-padding">
    <form method="POST" action="{{ route('admin.relatorio.gerencial') }}" class="smart-form client-form">
        @csrf
        <header>Relatório Gerencial</header>
        <fieldset>
            <div class="alert alert-info">
                Geração PDF via serviço externo (curl) está <strong>deferida</strong> nesta onda. Filtros abaixo espelham a tela legada.
            </div>
            <section>
                <label class="label"><strong>Beneficio (breakeven)</strong></label>
                <label class="select select-multiple">
                    <select name="beneficio_id[]" class="input_login custom-scroll" multiple size="6">
                        @foreach ($beneficioArr as $id => $nome)
                            <option value="{{ $id }}">{{ $nome }}</option>
                        @endforeach
                    </select>
                </label>
            </section>
        </fieldset>
        <footer>
            <button type="submit" class="btn btn-primary" disabled title="Deferido">Gerar (deferido)</button>
            <a href="{{ route('admin.relatorio.index') }}" class="btn btn-default">Voltar</a>
        </footer>
    </form>
</div>
@endsection
