@extends('layouts.admin')

@section('title', $title)

@section('content')
<section id="widget-grid">
    <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false"
         data-widget-colorbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">
        <header>
            <span class="widget-icon"><i class="fa fa-list"></i></span>
            <h2>{{ $title }}</h2>
        </header>
        <div class="widget-body" style="padding:15px;">
            <div class="alert alert-warning">
                Tela de entrada <strong>{{ $slug }}</strong> portada.
                Download/exportação <code>{{ $slug }}_down</code> e geração completa estão
                <strong>deferidos</strong> nesta onda (prioridade: entry points).
            </div>
            <p>
                <a class="btn btn-default" href="{{ route('admin.relatorio.index') }}">Voltar aos Relatórios</a>
                <a class="btn btn-default" href="{{ route('admin.relatorio.down', ['tipo' => $slug]) }}">
                    Tentar download (aviso)
                </a>
            </p>
        </div>
    </div>
</section>
@endsection
