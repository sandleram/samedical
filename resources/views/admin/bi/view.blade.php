@extends('layouts.admin')

@section('title', 'BI')

@section('content')
<div class="well no-padding smart-form client-form">
    <header>Visualização de BI</header>
    <fieldset>
        <section><label><strong>ID:</strong> {{ $row->id }}</label></section>
        <section><label><strong>Título:</strong> {{ $row->titulo }}</label></section>
        <section><label><strong>Subtítulo:</strong> {{ $row->subtitulo }}</label></section>
        <section><label><strong>Link:</strong> <a href="{{ $row->link }}" target="_blank">{{ $row->link }}</a></label></section>
        <section><label><strong>Ordem:</strong> {{ $row->ordem }}</label></section>
        <section><label><strong>Observação:</strong> {{ $row->observacao }}</label></section>
    </fieldset>
    <footer>
        <a href="{{ route('admin.bi.index') }}" class="btn btn-default">Voltar</a>
        @if ($permissao >= 2)
            <a href="{{ route('admin.bi.add', $row->id) }}" class="btn btn-primary">Editar</a>
        @endif
    </footer>
</div>
@endsection
