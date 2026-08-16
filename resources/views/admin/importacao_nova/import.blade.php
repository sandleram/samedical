@extends('layouts.admin')

@section('title', 'Importação Nova')

@section('content')
<section id="widget-grid">
    <div class="jarviswidget jarviswidget-color-blue" data-widget-editbutton="false">
        <header><span class="widget-icon"><i class="fa fa-list"></i></span><h2>Importação Nova</h2></header>
        <div class="widget-body" style="padding:15px;">
            <p><a class="btn btn-primary" href="{{ route('admin.importacao_nova.index') }}">Voltar à listagem</a></p>
        </div>
    </div>
</section>
@endsection
