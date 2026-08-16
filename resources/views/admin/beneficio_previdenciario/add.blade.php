@extends('layouts.admin')
@section('title', $title)
@section('breadcrumb')
    <li><a href="{{ route('admin.beneficio_previdenciario.index') }}">Benefícios Previdenciários</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection
@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form method="POST" action="{{ route('admin.beneficio_previdenciario.add', $row?->id) }}" class="smart-form client-form" id="bp-form">
                @csrf
                @if ($row)<input type="hidden" name="id" value="{{ $row->id }}">@endif
                <header>{{ $row ? 'Edição' : 'Cadastro' }} de Benefício Previdenciário</header>
                <fieldset>
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul style="margin:0;padding-left:18px;">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif
                    <section>
                        <label class="label">Beneficiário <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="beneficiario_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach (($beneficiarios ?? []) as $bid => $blabel)
                                    <option value="{{ $bid }}" @selected((string) old('beneficiario_id', $row->beneficiario_id ?? '') === (string) $bid)>{{ $blabel }}</option>
                                @endforeach
                            </select><i></i>
                        </label>
                    </section>
                    <section>
                        <label class="label">Empresa</label>
                        <label class="select">
                            <select name="empresa_id" class="input_login">
                                <option value="">Selecione...</option>
                                @foreach (($empresas ?? []) as $eid => $elabel)
                                    <option value="{{ $eid }}" @selected((string) old('empresa_id', $row->empresa_id ?? '') === (string) $eid)>{{ $elabel }}</option>
                                @endforeach
                            </select><i></i>
                        </label>
                    </section>
                    <section>
                        <label class="label">Espécie BP <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="especie_bp_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach (($especies ?? []) as $eid => $elabel)
                                    <option value="{{ $eid }}" @selected((string) old('especie_bp_id', $row->especie_bp_id ?? '') === (string) $eid)>{{ $elabel }}</option>
                                @endforeach
                            </select><i></i>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-2"><label class="label">NB</label><label class="input"><input type="number" name="nb" class="input_login" value="{{ old('nb', $row->nb ?? '') }}"></label></section>
                        <section class="col col-2"><label class="label">NIT</label><label class="input"><input type="number" name="nit" class="input_login" value="{{ old('nit', $row->nit ?? '') }}"></label></section>
                        <section class="col col-2"><label class="label">Nº requerimento</label><label class="input"><input type="number" name="num_requerimento" class="input_login" value="{{ old('num_requerimento', $row->num_requerimento ?? '') }}"></label></section>
                        <section class="col col-3"><label class="label">Espécie</label><label class="input"><input type="text" name="especie" maxlength="200" class="input_login" value="{{ old('especie', $row->especie ?? '') }}"></label></section>
                        <section class="col col-2"><label class="label">Situação</label><label class="input"><input type="text" name="situacao" maxlength="45" class="input_login" value="{{ old('situacao', $row->situacao ?? '') }}"></label></section>
                    </div>
                    <div class="row">
                        <section class="col col-2"><label class="label">Início</label><label class="input"><input type="date" name="data_inicio" class="input_login" value="{{ old('data_inicio', optional($row?->data_inicio)->format('Y-m-d')) }}"></label></section>
                        <section class="col col-2"><label class="label">Cessação</label><label class="input"><input type="date" name="data_cessacao" class="input_login" value="{{ old('data_cessacao', optional($row?->data_cessacao)->format('Y-m-d')) }}"></label></section>
                        <section class="col col-2"><label class="label">Próxima perícia</label><label class="input"><input type="date" name="data_proxima_pericia" class="input_login" value="{{ old('data_proxima_pericia', optional($row?->data_proxima_pericia)->format('Y-m-d')) }}"></label></section>
                        <section class="col col-3"><label class="label">Entrada requerimento</label><label class="input"><input type="date" name="data_entrada_requerimento" class="input_login" value="{{ old('data_entrada_requerimento', optional($row?->data_entrada_requerimento)->format('Y-m-d')) }}"></label></section>
                    </div>
                    <section>
                        <label class="label">Conclusão perícia médica</label>
                        <label class="textarea"><textarea name="conclusao_pericia_medica" rows="3" class="input_login">{{ old('conclusao_pericia_medica', $row->conclusao_pericia_medica ?? '') }}</textarea></label>
                    </section>
                    <section>
                        <label class="label">Status</label>
                        <label class="select">
                            <select name="status" class="input_login">
                                <option value="1" @selected((string) old('status', $row->status ?? '1') === '1')>Ativo</option>
                                <option value="0" @selected((string) old('status', $row->status ?? '1') === '0')>Inativo</option>
                            </select><i></i>
                        </label>
                    </section>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a class="btn btn-default" href="{{ route('admin.beneficio_previdenciario.index') }}">Cancelar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
