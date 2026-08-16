@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.usuario.index') }}">Usuários</a></li>
    <li>{{ $usuario ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="usuario-form"
                  method="POST"
                  action="{{ route('admin.usuario.add', $usuario?->id) }}"
                  class="smart-form client-form"
                  enctype="multipart/form-data">
                @csrf
                @if ($usuario)
                    <input type="hidden" name="id" value="{{ $usuario->id }}">
                @endif

                <header>
                    {{ $usuario ? 'Edição' : 'Cadastro' }} de Usuário
                    @if ($usuario)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.usuario.view', $usuario->id) }}" style="margin-top:-4px;">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    @endif
                </header>

                <fieldset>
                    @if ($usuario)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $usuario->id }}</label>
                            </section>
                            <section class="col col-6">
                                <label class="label" style="text-align: right;">
                                    <strong>Criado por:</strong>
                                    <i>{{ $usuario->usuarioCriador->nome ?? '-' }}</i>
                                </label>
                            </section>
                        </div>
                    @endif

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Perfil <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="perfil_id" class="input_login" required>
                                    @foreach ($perfilArr as $pid => $plabel)
                                        <option value="{{ $pid }}" @selected((string) old('perfil_id', $usuario->perfil_id ?? '') === (string) $pid)>{{ $plabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <div class="exibe_empresas_cliente row">
                        <div class="smart-form col col-6" style="margin-bottom:30px;">
                            <label class="label">Clientes</label>
                            <label class="select select-multiple">
                                <select name="cliente_id[]" id="UsuarioClienteId" class="input_login" multiple="multiple" style="height:200px;">
                                    @foreach ($selectClienteNew as $grupo)
                                        <optgroup label="{{ $grupo[0]['ge_nome'] ?? 'GE' }}">
                                            @foreach ($grupo as $item)
                                                @php
                                                    $sts = '';
                                                    if ((int) $item['cliente_status'] === 0) { $sts = ' - (Inativo)'; }
                                                    elseif ((int) $item['cliente_status'] === 2) { $sts = ' - (Excluído)'; }
                                                @endphp
                                                <option value="{{ $item['cliente_id'] }}"
                                                    @selected(in_array((int) $item['cliente_id'], $selectedClientes, true))>
                                                    {{ $item['cliente_nome'] }}{{ $sts }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="exibe_bi_usuario row">
                        <div class="smart-form col col-6" style="margin-bottom:30px;">
                            <label class="label">BI</label>
                            <label class="select select-multiple">
                                <select name="bi_id[]" id="UsuarioBiId" class="input_login" multiple="multiple" style="height:200px;">
                                    @foreach ($selectBi as $grupo)
                                        <optgroup label="{{ $grupo[0]['ge_nome'] ?? 'GE' }}">
                                            @foreach ($grupo as $item)
                                                <option value="{{ $item['bi_id'] }}"
                                                    @selected(in_array((int) $item['bi_id'], $selectedBis, true))>
                                                    {{ $item['titulo'] }} - {{ $item['subtitulo'] }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Apelido <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <i class="icon-append fa fa-smile-o"></i>
                                <input type="text" name="apelido" maxlength="10" class="input_login"
                                       value="{{ old('apelido', $usuario->apelido ?? '') }}" required>
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" name="nome" maxlength="65" class="input_login"
                                   value="{{ old('nome', $usuario->nome ?? '') }}" required>
                        </label>
                    </section>

                    <section>
                        <label class="label">Usuário <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" name="usuario" maxlength="60" class="input_login"
                                   value="{{ old('usuario', $usuario->usuario ?? '') }}" required>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Email <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <input type="email" name="email" maxlength="120" class="input_login"
                                       value="{{ old('email', $usuario->email ?? '') }}" required>
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Email Gestão</label>
                            <label class="input">
                                <i class="icon-append fa fa-envelope"></i>
                                <input type="email" name="email_gestao" maxlength="120" class="input_login"
                                       value="{{ old('email_gestao', $usuario->email_gestao ?? '') }}">
                            </label>
                        </section>
                    </div>

                    @if (! $usuario || $isRoot)
                        <div class="row exibe_senha">
                            <section class="col col-6">
                                <label class="label">Senha @if (! $usuario)<span class="campo_obrigatorio">*</span>@endif</label>
                                <label class="input">
                                    <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="senha" class="input_login" value=""
                                           @if (! $usuario) required @endif autocomplete="new-password">
                                </label>
                                @if ($usuario)
                                    <span class="note">Deixe em branco para manter a senha atual.</span>
                                @endif
                            </section>
                            <section class="col col-6">
                                <label class="label">Confirmação de Senha @if (! $usuario)<span class="campo_obrigatorio">*</span>@endif</label>
                                <label class="input">
                                    <i class="icon-append fa fa-lock"></i>
                                    <input type="password" name="retry_senha" class="input_login" value=""
                                           @if (! $usuario) required @endif autocomplete="new-password">
                                </label>
                            </section>
                        </div>
                    @endif

                    <header style="margin-top:20px; margin-bottom: 15px;">DADOS PESSOAIS</header>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Sexo</label>
                            <label class="select">
                                <select name="sexo" class="input_login">
                                    @foreach ($sexoArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected(old('sexo', $usuario->sexo ?? '') === $sid)>{{ $slabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">RG</label>
                            <label class="input">
                                <input type="text" name="rg" maxlength="15" class="input_login"
                                       value="{{ old('rg', $usuario->rg ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CPF</label>
                            <label class="input">
                                <input type="text" name="cpf" maxlength="15" class="cpf_mask input_login"
                                       value="{{ old('cpf', $usuario->cpf ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data de Nascimento</label>
                            <label class="input">
                                <input type="date" name="data_nascimento" class="input_login"
                                       value="{{ old('data_nascimento', optional($usuario->data_nascimento ?? null)->format('Y-m-d')) }}">
                            </label>
                        </section>
                    </div>

                    <header style="margin-top:20px; margin-bottom: 15px;">CONTATOS</header>
                    @foreach ([1, 2, 3] as $n)
                        <div class="row">
                            <section class="col col-2">
                                <label class="label">Telefone {{ $n }}</label>
                                <label class="select">
                                    <select name="tel{{ $n }}_tipo" class="input_login">
                                        @foreach ($telTipoArr as $tid => $tlabel)
                                            <option value="{{ $tid }}" @selected(old("tel{$n}_tipo", $usuario->{"tel{$n}_tipo"} ?? '') === $tid)>{{ $tlabel }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-6">
                                <label class="label">Telefone</label>
                                <label class="input">
                                    <i class="icon-append fa fa-phone"></i>
                                    <input type="text" name="tel{{ $n }}" maxlength="15" class="input_login tel_mask"
                                           value="{{ old("tel{$n}", $usuario->{"tel{$n}"} ?? '') }}">
                                </label>
                            </section>
                        </div>
                    @endforeach
                </fieldset>

                <fieldset>
                    <section style="margin-top:30px;">
                        <label class="label">Observações</label>
                        <label class="textarea">
                            <textarea name="observacao" rows="3" style="width:100%;">{{ old('observacao', $usuario->observacao ?? '') }}</textarea>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    @foreach ($statusArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('status', $usuario->status ?? '1') === (string) $sid)>{{ $slabel }}</option>
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
