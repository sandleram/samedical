@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.importacao.index') }}">Importação</a></li>
    <li>Cadastro</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form id="importacao-form" method="POST" action="{{ route('admin.importacao.add') }}"
                  enctype="multipart/form-data" class="smart-form client-form">
                @csrf
                <header>Cadastro de Importação</header>
                <fieldset>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label"><strong>Cliente:</strong> {{ $clienteNome }}</label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Tipo de Importação <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="tipo_importacao" id="ImportacaoTipoImportacao" class="input_login" required>
                                    @foreach ($tipoImportacaoArr as $k => $v)
                                        <option value="{{ $k }}" @selected(old('tipo_importacao') == $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                    <div class="row" style="margin-top: 30px; margin-bottom: 10px;">
                        <section class="col col-12" style="margin-bottom: 50px;">
                            <label class="label"><i class="fa fa-lg fa-file-o"></i> Arquivo <span class="campo_obrigatorio">*</span></label>
                            <label class="">
                                <input type="file" name="arquivo" class="btn btn-default" required>
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-12">
                            <label class="label">
                                Download:
                                <a href="{{ asset('files/uploads/importacao/modelo/todos_layouts_atualizado_v9.xlsx') }}">Modelo</a>
                            </label>
                            <p class="note">A carga linha-a-linha (carga_*) do legado está deferida; o arquivo é gravado e o registro criado.</p>
                        </section>
                    </div>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.importacao.index') }}" class="btn btn-default">Voltar</a>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
