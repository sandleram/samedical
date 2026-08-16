@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.afastado.index') }}">Afastados</a></li>
    <li>{{ $afastado ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form method="POST" action="{{ route('admin.afastado.add', $afastado?->id) }}" class="smart-form client-form" id="afastado-form">
                @csrf
                @if ($afastado)<input type="hidden" name="id" value="{{ $afastado->id }}">@endif
                <header>
                    {{ $afastado ? 'Edição' : 'Cadastro' }} de Afastado
                </header>
                <fieldset>
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul style="margin:0;padding-left:18px;">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif
                    @if ($afastado)
                        <section><label class="label"><strong>ID:</strong> {{ $afastado->id }}</label></section>
                    @endif
                    <section>
                        <label class="label">Beneficiário <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="beneficiario_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach ($beneficiarios as $bid => $blabel)
                                    <option value="{{ $bid }}" @selected((string) old('beneficiario_id', $afastado->beneficiario_id ?? '') === (string) $bid)>{{ $blabel }}</option>
                                @endforeach
                            </select><i></i>
                        </label>
                    </section>
                    <section>
                        <label class="label">Empresa</label>
                        <label class="select">
                            <select name="empresa_id" class="input_login">
                                <option value="">Selecione...</option>
                                @foreach ($empresas as $eid => $elabel)
                                    <option value="{{ $eid }}" @selected((string) old('empresa_id', $afastado->empresa_id ?? '') === (string) $eid)>{{ $elabel }}</option>
                                @endforeach
                            </select><i></i>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Situação <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="situacao" class="input_login" required>
                                    @foreach ($situacaoArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('situacao', $afastado->situacao ?? '') === (string) $sid)>{{ $slabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data Início</label>
                            <label class="input"><input type="date" name="data_inicio_afastamento" class="input_login" value="{{ old('data_inicio_afastamento', optional($afastado?->data_inicio_afastamento)->format('Y-m-d')) }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data Fim</label>
                            <label class="input"><input type="date" name="data_fim_afastamento" class="input_login" value="{{ old('data_fim_afastamento', optional($afastado?->data_fim_afastamento)->format('Y-m-d')) }}"></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">CID</label>
                            <label class="input"><input type="text" name="cid" maxlength="45" class="input_login" value="{{ old('cid', $afastado->cid ?? '') }}"></label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Tipo Afastamento</label>
                            <label class="input"><input type="text" name="tipo_afastamento" maxlength="45" class="input_login" value="{{ old('tipo_afastamento', $afastado->tipo_afastamento ?? '') }}"></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Ação Trabalhista</label>
                            <label class="select">
                                <select name="acao_trabalhista" class="input_login">
                                    @foreach ($simNaoArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('acao_trabalhista', $afastado->acao_trabalhista ?? '') === (string) $sid)>{{ $slabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Ação INSS</label>
                            <label class="select">
                                <select name="acao_inss" class="input_login">
                                    @foreach ($simNaoAcaoInssArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('acao_inss', $afastado->acao_inss ?? '') === (string) $sid)>{{ $slabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Limbo Previdenciário</label>
                            <label class="select">
                                <select name="limbo_previdenciario" class="input_login">
                                    @foreach ($simNaoArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('limbo_previdenciario', $afastado->limbo_previdenciario ?? '') === (string) $sid)>{{ $slabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Status</label>
                            <label class="select">
                                <select name="status" class="input_login">
                                    <option value="1" @selected((string) old('status', $afastado->status ?? '1') === '1')>Ativo</option>
                                    <option value="0" @selected((string) old('status', $afastado->status ?? '') === '0')>Inativo</option>
                                </select><i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a href="{{ route('admin.afastado.index') }}" class="btn btn-default">Voltar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
