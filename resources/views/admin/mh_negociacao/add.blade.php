@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_negociacao.index') }}">MH Negociação</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="mh_negociacao-form"
                  method="POST"
                  action="{{ route('admin.mh_negociacao.add', $row?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de MH Negociação
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

                    @if ($row)
                        <div class="row">
                            <section class="col col-6">
                                <label class="Bold"><strong>ID: </strong></label>
                                <label>{{ $row->id }}</label>
                            </section>
                        </div>
                    @endif

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Prestador <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="mh_prestador_id" class="input_login" required>
                                    <option value="">Selecione...</option>
                                    @foreach ($listPrestador as $pid => $pnome)
                                        <option value="{{ $pid }}" @selected((string) old('mh_prestador_id', $row->mh_prestador_id ?? '') === (string) $pid)>{{ $pnome }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Tipo Negócio <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="tipo_negocio" class="input_login" required>
                                    <option value="">Selecione...</option>
                                    @foreach ($ArrTipoNegocio as $k => $v)
                                        <option value="{{ $k }}" @selected((string) old('tipo_negocio', $row->tipo_negocio ?? '') === (string) $k)>{{ $v }}</option>
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
