@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.beneficiario.index') }}">Beneficiários</a></li>
    <li>{{ $beneficiario ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="beneficiario-form"
                  method="POST"
                  action="{{ route('admin.beneficiario.add', $beneficiario?->id) }}"
                  class="smart-form client-form"
                  enctype="multipart/form-data">
                @csrf
                @if ($beneficiario)
                    <input type="hidden" name="id" value="{{ $beneficiario->id }}">
                @endif

                @include('partials.admin.acoes_geral', [
                    'permissao' => $permissao,
                    'addRoute' => route('admin.beneficiario.add'),
                    'viewRoute' => $beneficiario ? route('admin.beneficiario.view', $beneficiario->id) : null,
                    'indexRoute' => route('admin.beneficiario.index'),
                    'novoLabel' => 'Novo Beneficiário',
                    'context' => 'add',
                ])

                <header>
                    {{ $beneficiario ? 'Edição' : 'Cadastro' }} de Beneficiário
                    @if ($beneficiario)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.beneficiario.view', $beneficiario->id) }}" style="margin-top:-4px;">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    @endif
                </header>

                <fieldset>
                    @if ($beneficiario)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $beneficiario->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" name="nome" maxlength="65" class="input_login"
                                   value="{{ old('nome', $beneficiario->nome ?? '') }}" required>
                        </label>
                    </section>

                    <section>
                        <label class="label">Nome Social</label>
                        <label class="input">
                            <i class="icon-append fa fa-user"></i>
                            <input type="text" name="nome_social" maxlength="65" class="input_login"
                                   value="{{ old('nome_social', $beneficiario->nome_social ?? '') }}">
                        </label>
                    </section>

                    <section>
                        <label class="label">Email <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <i class="icon-append fa fa-envelope"></i>
                            <input type="email" name="email" maxlength="120" class="input_login"
                                   value="{{ old('email', $beneficiario->email ?? '') }}" required>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Empresa <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="empresa_id" class="input_login" required>
                                    <option value="">Empresa...</option>
                                    @foreach ($empresas as $empId => $empLabel)
                                        <option value="{{ $empId }}" @selected((string) old('empresa_id', $beneficiario->empresa_id ?? '') === (string) $empId)>
                                            {{ $empLabel }}
                                        </option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Situação</label>
                            <label class="select">
                                <select name="situacao" class="input_login">
                                    <option value="">Situação...</option>
                                    <option value="Ativo" @selected(old('situacao', $beneficiario->situacao ?? '') === 'Ativo')>Ativo</option>
                                    <option value="Inativo" @selected(old('situacao', $beneficiario->situacao ?? '') === 'Inativo')>Inativo</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Matrícula</label>
                            <label class="input">
                                <input type="text" name="cod_matricula" class="input_login" maxlength="100"
                                       value="{{ old('cod_matricula', $beneficiario->cod_matricula ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">PIS</label>
                            <label class="input">
                                <input type="text" name="pis" class="input_login" maxlength="45"
                                       value="{{ old('pis', $beneficiario->pis ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Informações do Beneficio</h4>
                    <section>
                        <label class="label">Benefício</label>
                        <label class="input">
                            <input type="text" name="beneficio" maxlength="150" class="input_login"
                                   value="{{ old('beneficio', $beneficiario->beneficio ?? '') }}">
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Valor (R$)</label>
                            <label class="input">
                                <input type="text" name="valor_do_seguro" class="input_login money_mask" maxlength="20"
                                       value="{{ old('valor_do_seguro', $beneficiario->valor_do_seguro ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Dados Pessoais</h4>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">CPF <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <input type="text" name="cpf" class="cpf_mask" maxlength="15"
                                       value="{{ old('cpf', $beneficiario->cpf ?? '') }}" required>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">RG</label>
                            <label class="input">
                                <input type="text" name="rg" class="rg_mask" maxlength="15"
                                       value="{{ old('rg', $beneficiario->rg ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Sexo <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="sexo" class="input_login" required>
                                    @foreach ($sexoArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('sexo', $beneficiario->sexo ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Estado Civil</label>
                            <label class="select">
                                <select name="estado_civil" class="input_login">
                                    @foreach ($estadoCivilArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('estado_civil', $beneficiario->estado_civil ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data de Nascimento <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <input type="date" name="data_nascimento" class="input_login" required
                                       value="{{ old('data_nascimento', optional($beneficiario?->data_nascimento)->format('Y-m-d')) }}">
                            </label>
                        </section>
                    </div>

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Altura</label>
                            <label class="input">
                                <i class="icon-append fa fa-male"></i>
                                <input type="text" name="altura" class="altura_mask" maxlength="4"
                                       value="{{ old('altura', $alturaDisplay) }}" placeholder="_,__">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Peso</label>
                            <label class="input">
                                <i class="icon-append fa fa-dashboard"></i>
                                <input type="text" name="peso" class="peso_mask" maxlength="6"
                                       value="{{ old('peso', $pesoDisplay) }}" placeholder="___,_">
                            </label>
                        </section>
                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Endereço</h4>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Endereço Completo</label>
                            <label class="input">
                                <input type="text" name="endereco" maxlength="200" class="input_login"
                                       value="{{ old('endereco', $beneficiario->endereco ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Bairro</label>
                            <label class="input">
                                <input type="text" name="bairro" maxlength="150" class="input_login"
                                       value="{{ old('bairro', $beneficiario->bairro ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Cidade</label>
                            <label class="input">
                                <input type="text" name="cidade" maxlength="60" class="input_login"
                                       value="{{ old('cidade', $beneficiario->cidade ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-1">
                            <label class="label">Estado</label>
                            <label class="input">
                                <input type="text" name="estado" maxlength="2" class="input_login"
                                       value="{{ old('estado', $beneficiario->estado ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CEP</label>
                            <label class="input">
                                <input type="text" name="cep" maxlength="9" class="cep_mask input_login"
                                       value="{{ old('cep', $beneficiario->cep ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Dados Bancários</h4>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Agência</label>
                            <label class="input">
                                <input type="text" name="agencia" maxlength="50" class="input_login"
                                       value="{{ old('agencia', $beneficiario->agencia ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Conta</label>
                            <label class="input">
                                <input type="text" name="conta" maxlength="50" class="input_login"
                                       value="{{ old('conta', $beneficiario->conta ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Tipo de Conta</label>
                            <label class="input">
                                <input type="text" name="tipo_de_conta" maxlength="50" class="input_login"
                                       value="{{ old('tipo_de_conta', $beneficiario->tipo_de_conta ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Informações Profissionais</h4>
                    <div class="row">
                        <section class="col col-6">
                            <label class="label">Profissão</label>
                            <label class="input">
                                <input type="text" name="profissao" maxlength="50" class="input_login"
                                       value="{{ old('profissao', $beneficiario->profissao ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-6">
                            <label class="label">Ocupação</label>
                            <label class="input">
                                <input type="text" name="ocupacao" maxlength="50" class="input_login"
                                       value="{{ old('ocupacao', $beneficiario->ocupacao ?? '') }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Pessoa Politicamente Exposta?</label>
                            <label class="input">
                                <input type="text" name="pessoa_politicamente_exposta" maxlength="5" class="input_login"
                                       value="{{ old('pessoa_politicamente_exposta', $beneficiario->pessoa_politicamente_exposta ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Realiza atividade perigosa?</label>
                            <label class="input">
                                <input type="text" name="realiza_alguma_atividade_perigosa_na_profissao" maxlength="5" class="input_login"
                                       value="{{ old('realiza_alguma_atividade_perigosa_na_profissao', $beneficiario->realiza_alguma_atividade_perigosa_na_profissao ?? '') }}">
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Possui deficiência?</label>
                            <label class="input">
                                <input type="text" name="possui_deficiencia" maxlength="5" class="input_login"
                                       value="{{ old('possui_deficiencia', $beneficiario->possui_deficiencia ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <h4 style="border-bottom:1px dotted #d3d3d3;margin-top:30px; margin-bottom:10px; font-weight: bold;">Contatos</h4>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Tipo Tel. Principal <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="telefone_tipo" class="input_login" required>
                                    @foreach ($telTipoArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('telefone_tipo', $beneficiario->telefone_tipo ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Telefone Principal <span class="campo_obrigatorio">*</span></label>
                            <label class="input">
                                <i class="icon-append fa fa-phone"></i>
                                <input type="text" name="telefone" class="tel_mask input_login" maxlength="15" required
                                       value="{{ old('telefone', $beneficiario->telefone ?? '') }}">
                            </label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Tipo Tel. 2</label>
                            <label class="select">
                                <select name="telefone1_tipo" class="input_login">
                                    @foreach ($telTipoArr as $val => $label)
                                        <option value="{{ $val }}" @selected((string) old('telefone1_tipo', $beneficiario->telefone1_tipo ?? '') === (string) $val)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Telefone 2</label>
                            <label class="input">
                                <i class="icon-append fa fa-phone"></i>
                                <input type="text" name="telefone1" class="tel_mask input_login" maxlength="15"
                                       value="{{ old('telefone1', $beneficiario->telefone1 ?? '') }}">
                            </label>
                        </section>
                    </div>

                    <section>
                        <label class="label">Observação</label>
                        <label class="textarea">
                            <textarea name="observacao" rows="4" class="input_login">{{ old('observacao', $beneficiario->observacao ?? '') }}</textarea>
                        </label>
                    </section>
                </fieldset>

                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.beneficiario.index') }}" class="btn btn-default">Cancelar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
