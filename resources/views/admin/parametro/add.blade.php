@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.parametro.index') }}">Parâmetros</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="parametro-form"
                  method="POST"
                  action="{{ route('admin.parametro.add', $row?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de Parâmetro
                    @if ($row)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.parametro.view', $row->id) }}" style="margin-top:-4px;">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    @endif
                </header>

                <fieldset>
                    @if ($row)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $row->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="nome" maxlength="100" class="input_login"
                                   value="{{ old('nome', $row->nome ?? '') }}" required>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Tipo <span class="campo_obrigatorio">*</span></label>
                            <label class="select tipo_old">
                                <select name="tipo" class="input_login" id="ParametroTipo">
                                    <option value="">Selecione...</option>
                                    @foreach ($tipoArr as $tipoVal)
                                        <option value="{{ $tipoVal }}" @selected((string) old('tipo', $row->tipo ?? '') === (string) $tipoVal)>{{ $tipoVal }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                            @if ($isRoot)
                                <label class="label margin-top-5">
                                    <a href="javascript:void(0);" class="link_tipo_novo">Modificar</a>
                                </label>
                                <label class="input tipo_new" style="display:none;">
                                    <input type="text" name="tipo_novo" id="ParametroTipoNovo" maxlength="40" class="input_login"
                                           value="{{ old('tipo_novo', $row->tipo ?? '') }}" placeholder="Tipo">
                                </label>
                            @endif
                        </section>
                    </div>

                    <section>
                        <label class="label">Valor <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="valor" maxlength="100" class="input_login"
                                   value="{{ old('valor', $row->valor ?? '') }}" required>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    <option value="1" @selected((string) old('status', $row->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $row->status ?? '1') === '0')>Inativo</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>
            </form>
        </div>
    </div>
</div>

@if ($isRoot)
<script type="text/javascript">
    $(document).ready(function () {
        $('.link_tipo_novo').click(function () {
            $('.link_tipo_novo').fadeOut('slow');
            $('.tipo_old').fadeOut('slow');
            $('.tipo_new').fadeIn('slow');
        });
    });
</script>
@endif
@endsection
