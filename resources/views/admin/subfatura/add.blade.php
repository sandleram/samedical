@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.subfatura.index') }}">Subfaturas</a></li>
    <li>{{ $subfatura ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form id="subfatura-form"
                  method="POST"
                  action="{{ route('admin.subfatura.add', $subfatura?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($subfatura)
                    <input type="hidden" name="id" value="{{ $subfatura->id }}">
                @endif

                <header>
                    {{ $subfatura ? 'Edição' : 'Cadastro' }} de Subfatura
                    @if ($subfatura)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.subfatura.view', $subfatura->id) }}" style="margin-top:-4px;">
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

                    @if ($subfatura)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $subfatura->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Benefício <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="beneficio_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach ($beneficios as $optId => $optLabel)
                                    <option value="{{ $optId }}" @selected((string) old('beneficio_id', $subfatura->beneficio_id ?? '') === (string) $optId)>
                                        {{ $optLabel }}
                                    </option>
                                @endforeach
                            </select>
                            <i></i>
                        </label>
                    </section>
                    <section>
                        <label class="label">Descrição <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="descricao" class="input_login" maxlength="450"
                                   value="{{ old('descricao', $subfatura->descricao ?? '') }}" required>
                        </label>
                    </section>
                    <section>
                        <label class="label">Código <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="codigo" class="input_login" maxlength="45"
                                   value="{{ old('codigo', $subfatura->codigo ?? '') }}" required>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data de Cancelamento</label>
                            <label class="input">
                                <input type="date" name="data_cancelamento" class="input_login"
                                       value="{{ old('data_cancelamento', optional($subfatura?->data_cancelamento)->format('Y-m-d')) }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    <option value="1" @selected((string) old('status', $subfatura->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $subfatura->status ?? '') === '0')>Inativo</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.subfatura.index') }}" class="btn btn-default">Voltar</a>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
