@extends('layouts.admin')

@section('title', 'Importação')

@section('breadcrumb')
    <li><a href="{{ route('admin.importacao.index') }}">Importação</a></li>
    <li>Resultado</li>
@endsection

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-import"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Importação</h2>
                </header>
                <div class="widget-body" style="padding:15px;">
                    @if ($importacaoId)
                        <p>Registro de importação <strong>#{{ $importacaoId }}</strong> criado.</p>
                    @endif
                    <p>
                        <a class="btn btn-primary" href="{{ route('admin.importacao.index') }}">Voltar à listagem</a>
                        <a class="btn btn-default" href="{{ route('admin.importacao.add') }}">Nova importação</a>
                        <a class="btn btn-default" href="{{ route('admin.importacao.validacao') }}">Validação</a>
                    </p>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
