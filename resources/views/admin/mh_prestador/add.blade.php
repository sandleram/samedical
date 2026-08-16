@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_prestador.index') }}">MH Prestador</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="mh_prestador-form"
                  method="POST"
                  action="{{ route('admin.mh_prestador.add', $row?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de MH Prestador
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

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="nome" maxlength="150" class="input_login"
                                   value="{{ old('nome', $row->nome ?? '') }}" required>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">HubSpot ID</label>
                            <label class="input">
                                <input type="text" name="id_hubspot" maxlength="45" class="input_login"
                                       value="{{ old('id_hubspot', $row->id_hubspot ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Cidade</label>
                            <label class="input">
                                <input type="text" name="cidade" maxlength="60" class="input_login"
                                       value="{{ old('cidade', $row->cidade ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-1">
                            <label class="label">UF</label>
                            <label class="input">
                                <input type="text" name="estado" maxlength="2" class="input_login"
                                       value="{{ old('estado', $row->estado ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Praça</label>
                            <label class="input">
                                <input type="text" name="praca" maxlength="100" class="input_login"
                                       value="{{ old('praca', $row->praca ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Atividade</label>
                            <label class="input">
                                <input type="text" name="atividade" maxlength="255" class="input_login"
                                       value="{{ old('atividade', $row->atividade ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Descrição</label>
                        <label class="textarea">
                            <textarea name="descricao" class="input_login" rows="4">{{ old('descricao', $row->descricao ?? '') }}</textarea>
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
