@extends('layouts.admin')

@section('title', $title)

@section('content')
<div class="well no-padding">
    <form method="POST" action="{{ route('admin.bi.add', $row?->id) }}" class="smart-form client-form" id="bi-form">
        @csrf
        <header>{{ $row ? 'Edição' : 'Cadastro' }} de BI</header>
        <fieldset>
            @if ($row)
                <div class="row">
                    <section class="col col-6">
                        <label class="label"><strong>ID:</strong> {{ $row->id }}</label>
                    </section>
                </div>
            @endif
            <section>
                <label class="label">Título <span class="campo_obrigatorio">*</span></label>
                <label class="input">
                    <input type="text" name="titulo" class="input_login" maxlength="20"
                           value="{{ old('titulo', $row?->titulo) }}" required>
                </label>
            </section>
            <section>
                <label class="label">Subtítulo</label>
                <label class="input">
                    <input type="text" name="subtitulo" class="input_login" maxlength="60"
                           value="{{ old('subtitulo', $row?->subtitulo) }}">
                </label>
            </section>
            <section>
                <label class="label">Link <span class="campo_obrigatorio">*</span></label>
                <label class="input">
                    <input type="text" name="link" class="input_login" maxlength="255"
                           value="{{ old('link', $row?->link) }}" required>
                </label>
            </section>
            <section>
                <label class="label">Ordem</label>
                <label class="input">
                    <input type="number" name="ordem" class="input_login"
                           value="{{ old('ordem', $row?->ordem) }}">
                </label>
            </section>
            <section>
                <label class="label">Cliente ID (opcional — vazio = GE)</label>
                <label class="input">
                    <input type="number" name="cliente_id" class="input_login"
                           value="{{ old('cliente_id', $row?->cliente_id) }}">
                </label>
            </section>
            <section>
                <label class="label">Observação</label>
                <label class="textarea">
                    <textarea name="observacao" class="input_login" rows="3">{{ old('observacao', $row?->observacao) }}</textarea>
                </label>
            </section>
            <section>
                <label class="label">Status</label>
                <label class="select">
                    <select name="status" class="input_login">
                        <option value="1" @selected((string) old('status', $row?->status ?? 1) === '1')>Ativo</option>
                        <option value="0" @selected((string) old('status', $row?->status ?? 1) === '0')>Inativo</option>
                    </select>
                    <i></i>
                </label>
            </section>
        </fieldset>
        <footer>
            <button type="submit" class="btn btn-primary">Salvar</button>
            <a href="{{ route('admin.bi.index') }}" class="btn btn-default">Voltar</a>
        </footer>
    </form>
</div>
@endsection
