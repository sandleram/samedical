@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.procedimento.index') }}">Procedimentos</a></li>
    <li>{{ $procedimento ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form id="procedimento-form"
                  method="POST"
                  action="{{ route('admin.procedimento.add', $procedimento?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($procedimento)
                    <input type="hidden" name="id" value="{{ $procedimento->id }}">
                @endif

                <header>
                    {{ $procedimento ? 'Edição' : 'Cadastro' }} de Procedimento
                    @if ($procedimento)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.procedimento.view', $procedimento->id) }}" style="margin-top:-4px;">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    @endif
                </header>

                <fieldset>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul style="margin:0;padding-left:18px;">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if ($procedimento)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $procedimento->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Código</label>
                        <label class="input">
                            <input type="text" name="cod_procedimento" class="input_login" maxlength="50"
                                   value="{{ old('cod_procedimento', $procedimento->cod_procedimento ?? '') }}">
                        </label>
                    </section>
                    <section>
                        <label class="label">Descrição <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="ds_procedimento" class="input_login" maxlength="300"
                                   value="{{ old('ds_procedimento', $procedimento->ds_procedimento ?? '') }}" required>
                        </label>
                    </section>
                    <section>
                        <label class="label">Tipo</label>
                        <label class="input">
                            <input type="text" name="tipo_procedimento" class="input_login" maxlength="200"
                                   value="{{ old('tipo_procedimento', $procedimento->tipo_procedimento ?? '') }}">
                        </label>
                    </section>
                    <section>
                        <label class="label">Grupo</label>
                        <label class="input">
                            <input type="text" name="Grupo" class="input_login" maxlength="100"
                                   value="{{ old('Grupo', $procedimento->Grupo ?? '') }}">
                        </label>
                    </section>
                    <section>
                        <label class="label">Subgrupo</label>
                        <label class="input">
                            <input type="text" name="Subgrupo" class="input_login" maxlength="250"
                                   value="{{ old('Subgrupo', $procedimento->Subgrupo ?? '') }}">
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    <option value="1" @selected((string) old('status', $procedimento->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $procedimento->status ?? '') === '0')>Inativo</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.procedimento.index') }}" class="btn btn-default">Voltar</a>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
