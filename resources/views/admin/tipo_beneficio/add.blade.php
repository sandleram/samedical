@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.tipo_beneficio.index') }}">Tipos de Benefício</a></li>
    <li>{{ $tipoBeneficio ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form id="tipo_beneficio-form"
                  method="POST"
                  action="{{ route('admin.tipo_beneficio.add', $tipoBeneficio?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($tipoBeneficio)
                    <input type="hidden" name="id" value="{{ $tipoBeneficio->id }}">
                @endif

                <header>
                    {{ $tipoBeneficio ? 'Edição' : 'Cadastro' }} de Tipo de Benefício
                    @if ($tipoBeneficio)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.tipo_beneficio.view', $tipoBeneficio->id) }}" style="margin-top:-4px;">
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

                    @if ($tipoBeneficio)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $tipoBeneficio->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Descrição <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="descricao" class="input_login" maxlength="45"
                                   value="{{ old('descricao', $tipoBeneficio->descricao ?? '') }}" required>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data de Cancelamento</label>
                            <label class="input">
                                <input type="date" name="data_cancelamento" class="input_login"
                                       value="{{ old('data_cancelamento', optional($tipoBeneficio?->data_cancelamento)->format('Y-m-d')) }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    <option value="1" @selected((string) old('status', $tipoBeneficio->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $tipoBeneficio->status ?? '') === '0')>Inativo</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.tipo_beneficio.index') }}" class="btn btn-default">Voltar</a>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
