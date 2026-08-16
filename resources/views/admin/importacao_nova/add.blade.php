@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.importacao_nova.index') }}">Importação Nova</a></li>
    <li>Cadastro</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form method="POST" action="{{ route('admin.importacao_nova.add') }}"
                  enctype="multipart/form-data" class="smart-form client-form" id="importacao_nova-form">
                @csrf
                <header>Cadastro de Importação Nova</header>
                <fieldset>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Tipo de Importação <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="tipo_importacao" class="input_login" required>
                                    @foreach ($tipoImportacaoArr as $k => $v)
                                        <option value="{{ $k }}" @selected(old('tipo_importacao') == $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row" style="margin-top: 20px;">
                        <section class="col col-12">
                            <label class="label">Arquivo <span class="campo_obrigatorio">*</span></label>
                            <input type="file" name="arquivo" class="btn btn-default" required>
                            <p class="note">Arquivo fica em <code>files/uploads/importacao_nova/aguardando</code> com status pending. Job <code>processar_arquivo</code> deferido.</p>
                        </section>
                    </div>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.importacao_nova.index') }}" class="btn btn-default">Voltar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
