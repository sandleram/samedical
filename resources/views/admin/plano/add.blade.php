@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.plano.index') }}">Planos</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="plano-form"
                  method="POST"
                  action="{{ route('admin.plano.add', $row?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de Plano
                    @if ($row)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.plano.view', $row->id) }}" style="margin-top:-4px;">
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

                    <section>
                        <label class="label">Código Operadora</label>
                        <label class="input">
                            <input type="text" name="codigo_operadora" maxlength="50" class="input_login"
                                   value="{{ old('codigo_operadora', $row->codigo_operadora ?? '') }}">
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Operadora</label>
                            <label class="select">
                                <select name="operadora_id" class="input_login">
                                    @foreach ($operadoraArr as $optId => $optLabel)
                                        <option value="{{ $optId }}" @selected((string) old('operadora_id', $row->operadora_id ?? '') === (string) $optId)>{{ $optLabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Tipo de Beneficio</label>
                            <label class="select">
                                <select name="tipo_beneficio_id" class="input_login">
                                    @foreach ($tipoBeneficioArr as $optId => $optLabel)
                                        <option value="{{ $optId }}" @selected((string) old('tipo_beneficio_id', $row->tipo_beneficio_id ?? '') === (string) $optId)>{{ $optLabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

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
@endsection
