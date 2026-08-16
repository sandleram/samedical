@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_critico.index') }}">MH Crítico</a></li>
    <li><a href="{{ route('admin.mh_critico_historico.index', $mh_critico_id) }}">Histórico</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="mh_critico_historico-form"
                  method="POST"
                  action="{{ route('admin.mh_critico_historico.add', [$mh_critico_id, $row?->id]) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de MH Crítico Histórico
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
                            <label class="label">Ciclo <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="ciclo" class="input_login" required>
                                    @foreach ($ArrCiclo as $k => $v)
                                        <option value="{{ $k }}" @selected((string) old('ciclo', $row->ciclo ?? '') === (string) $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Status Ciclo <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status_ciclo" class="input_login" required>
                                    @foreach ($ArrStatusCiclo as $k => $v)
                                        <option value="{{ $k }}" @selected((string) old('status_ciclo', $row->status_ciclo ?? '') === (string) $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Descrição <span class="campo_obrigatorio">*</span></label>
                        <label class="textarea">
                            <textarea name="descricao" class="input_login" rows="5" required>{{ old('descricao', $row->descricao ?? '') }}</textarea>
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
@endsection
