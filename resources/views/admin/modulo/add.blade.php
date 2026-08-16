@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.modulo.index') }}">Módulos</a></li>
    <li>{{ $modulo ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="modulo-form"
                  method="POST"
                  action="{{ route('admin.modulo.add', $modulo?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($modulo)
                    <input type="hidden" name="id" value="{{ $modulo->id }}">
                @endif

                <header>
                    {{ $modulo ? 'Edição' : 'Cadastro' }} de Módulo
                    @if ($modulo)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.modulo.view', $modulo->id) }}" style="margin-top:-4px;">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    @endif
                </header>

                <fieldset>
                    @if ($modulo)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $modulo->id }}</label>
                            </section>
                        </div>
                    @endif

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Módulo Pai <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="modulo_id" class="input_login" required>
                                    @foreach ($moduloArr as $mid => $mlabel)
                                        <option value="{{ $mid }}" @selected((string) old('modulo_id', $modulo->modulo_id ?? '0') === (string) $mid)>{{ $mlabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="nome" maxlength="45" class="input_login"
                                   value="{{ old('nome', $modulo->nome ?? '') }}" required>
                        </label>
                    </section>

                    <section>
                        <label class="label">Controller <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="controller" maxlength="45" class="input_login"
                                   value="{{ old('controller', $modulo->controller ?? '') }}" required>
                        </label>
                    </section>

                    <section>
                        <label class="label">Ordem</label>
                        <label class="input">
                            <input type="text" name="order" maxlength="3" class="input_login"
                                   value="{{ old('order', $modulo->order ?? '') }}">
                        </label>
                    </section>

                    <section>
                        <label class="label">Menu</label>
                        <label class="input">
                            <input type="text" name="menu" maxlength="3" class="input_login"
                                   value="{{ old('menu', $modulo->menu ?? '') }}">
                            <label class="note">
                                <b>Exemplos</b><br/>
                                <b>0</b> - Sem SubItens com link direto <br/>
                                <b>1</b> - Nível Pai para receber SubMenu<br/>
                                <b>2</b> - Nível com SubMenu (CRUD) (Pai ou Sub)<br/>
                            </label>
                        </label>
                    </section>

                    <section>
                        <label class="label">Ícone</label>
                        <label class="input">
                            <input type="text" name="icon" maxlength="35" class="input_login"
                                   value="{{ old('icon', $modulo->icon ?? '') }}">
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    @foreach ($statusArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('status', $modulo->status ?? '1') === (string) $sid)>{{ $slabel }}</option>
                                    @endforeach
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
