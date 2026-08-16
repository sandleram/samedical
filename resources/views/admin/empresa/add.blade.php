@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.empresa.index') }}">Empresas</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="empresa-form"
                  method="POST"
                  action="{{ route('admin.empresa.add', $row?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de Empresa
                    @if ($row)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.empresa.view', $row->id) }}" style="margin-top:-4px;">
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

                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <input type="text" name="nome" class="input_login" maxlength="45"
                                       value="{{ old('nome', $row->nome ?? '') }}" required placeholder="Empresa">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Razão Social</label>
                            <label class="input">
                                <input type="text" name="razao_social" class="input_login" maxlength="100"
                                       value="{{ old('razao_social', $row->razao_social ?? '') }}" placeholder="Razão Social">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Nome Fantasia</label>
                            <label class="input">
                                <input type="text" name="nome_fantasia" class="input_login" maxlength="100"
                                       value="{{ old('nome_fantasia', $row->nome_fantasia ?? '') }}" placeholder="Nome Fantasia">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">CNPJ <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <input type="text" name="cnpj" class="cnpj_mask input_login" maxlength="19"
                                       value="{{ old('cnpj', $row->cnpj ?? '') }}" required placeholder="CNPJ">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-5">
                            <label class="label">Inscrição Estadual</label>
                            <label class="input">
                                <input type="text" name="inscricao_estadual" class="input_login" maxlength="25"
                                       value="{{ old('inscricao_estadual', $row->inscricao_estadual ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-5">
                            <label class="label">Inscrição Municipal</label>
                            <label class="input">
                                <input type="text" name="inscricao_municipal" class="input_login" maxlength="25"
                                       value="{{ old('inscricao_municipal', $row->inscricao_municipal ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Nº Funcionários</label>
                            <label class="input">
                                <input type="number" name="numero_funcionarios" class="input_login"
                                       value="{{ old('numero_funcionarios', $row->numero_funcionarios ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Descrição</label>
                        <label class="textarea">
                            <textarea name="descricao" rows="4" class="input_login" style="width:100%;">{{ old('descricao', $row->descricao ?? '') }}</textarea>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Tipo</label>
                            <label class="select">
                                <select name="tipo" class="input_login">
                                    @foreach ($tipoArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('tipo', $row->tipo ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Porte</label>
                            <label class="select">
                                <select name="porte" class="input_login">
                                    @foreach ($porteArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('porte', $row->porte ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Faturamento</label>
                            <label class="select">
                                <select name="faturamento" class="input_login">
                                    @foreach ($faturamentoArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('faturamento', $row->faturamento ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-8">
                            <label class="label">Endereço</label>
                            <label class="input">
                                <input type="text" name="endereco" class="input_login" maxlength="100"
                                       value="{{ old('endereco', $row->endereco ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-1">
                            <label class="label">Número</label>
                            <label class="input">
                                <input type="text" name="numero" class="input_login" maxlength="10"
                                       value="{{ old('numero', $row->numero ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Complemento</label>
                            <label class="input">
                                <input type="text" name="complemento" class="input_login" maxlength="100"
                                       value="{{ old('complemento', $row->complemento ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Bairro</label>
                            <label class="input">
                                <input type="text" name="bairro" class="input_login" maxlength="50"
                                       value="{{ old('bairro', $row->bairro ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Cidade</label>
                            <label class="input">
                                <input type="text" name="cidade" class="input_login" maxlength="50"
                                       value="{{ old('cidade', $row->cidade ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-1">
                            <label class="label">UF</label>
                            <label class="input">
                                <input type="text" name="estado" class="input_login" maxlength="2"
                                       value="{{ old('estado', $row->estado ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">CEP</label>
                            <label class="input">
                                <input type="text" name="cep" class="cep_mask input_login" maxlength="9"
                                       value="{{ old('cep', $row->cep ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Telefone</label>
                            <label class="input">
                                <input type="text" name="telefone" class="tel_mask input_login" maxlength="50"
                                       value="{{ old('telefone', $row->telefone ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">E-mail</label>
                            <label class="input">
                                <input type="email" name="email" class="input_login" maxlength="100"
                                       value="{{ old('email', $row->email ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Site</label>
                            <label class="input">
                                <input type="text" name="site" class="input_login" maxlength="250"
                                       value="{{ old('site', $row->site ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    <option value="1" @selected((string) old('status', $row->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $row->status ?? '') === '0')>Inativo</option>
                                    <option value="2" @selected((string) old('status', $row->status ?? '') === '2')>Excluído</option>
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
