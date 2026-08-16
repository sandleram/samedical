@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.beneficio.index') }}">Benefícios</a></li>
    <li>{{ $beneficio ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form id="beneficio-form"
                  method="POST"
                  action="{{ route('admin.beneficio.add', $beneficio?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($beneficio)
                    <input type="hidden" name="id" value="{{ $beneficio->id }}">
                @endif

                <header>
                    {{ $beneficio ? 'Edição' : 'Cadastro' }} de Benefício
                    @if ($beneficio)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.beneficio.view', $beneficio->id) }}" style="margin-top:-4px;">
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

                    @if ($beneficio)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $beneficio->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Descrição <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="descricao" class="input_login" maxlength="100"
                                   value="{{ old('descricao', $beneficio->descricao ?? '') }}" required>
                        </label>
                    </section>
                    <section>
                        <label class="label">Breakeven</label>
                        <label class="input">
                            <input type="text" name="breakeven" class="input_login" maxlength="11"
                                   value="{{ old('breakeven', $beneficio->breakeven ?? '') }}">
                        </label>
                    </section>
                    <section>
                        <label class="label">Contrato</label>
                        <label class="input">
                            <input type="text" name="contrato" class="input_login" maxlength="50"
                                   value="{{ old('contrato', $beneficio->contrato ?? '') }}">
                        </label>
                    </section>
                    <section>
                        <label class="label">Operadora <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="operadora_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach ($operadoras as $optId => $optLabel)
                                    <option value="{{ $optId }}" @selected((string) old('operadora_id', $beneficio->operadora_id ?? '') === (string) $optId)>
                                        {{ $optLabel }}
                                    </option>
                                @endforeach
                            </select>
                            <i></i>
                        </label>
                    </section>
                    <section>
                        <label class="label">Tipo de Benefício <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="tipo_beneficio_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach ($tiposBeneficio as $optId => $optLabel)
                                    <option value="{{ $optId }}" @selected((string) old('tipo_beneficio_id', $beneficio->tipo_beneficio_id ?? '') === (string) $optId)>
                                        {{ $optLabel }}
                                    </option>
                                @endforeach
                            </select>
                            <i></i>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data de Cancelamento</label>
                            <label class="input">
                                <input type="date" name="data_cancelamento" class="input_login"
                                       value="{{ old('data_cancelamento', optional($beneficio?->data_cancelamento)->format('Y-m-d')) }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    <option value="1" @selected((string) old('status', $beneficio->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $beneficio->status ?? '') === '0')>Inativo</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.beneficio.index') }}" class="btn btn-default">Voltar</a>
                    <span class="campo_obrigatorio">* Campos Obrigatórios</span>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
