@extends('layouts.admin')
@section('title', $title)
@section('breadcrumb')
    <li><a href="{{ route('admin.absenteismo.index') }}">Absenteísmo</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection
@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form method="POST" action="{{ route('admin.absenteismo.add', $row?->id) }}" class="smart-form client-form" id="absenteismo-form">
                @csrf
                @if ($row)<input type="hidden" name="id" value="{{ $row->id }}">@endif
                <header>{{ $row ? 'Edição' : 'Cadastro' }} de Absenteísmo</header>
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
                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Data Saída</label>
                            <label class="input"><input type="date" name="data_saida" class="input_login" value="{{ old('data_saida', optional($row?->data_saida)->format('Y-m-d')) }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Data Retorno</label>
                            <label class="input"><input type="date" name="data_retorno" class="input_login" value="{{ old('data_retorno', optional($row?->data_retorno)->format('Y-m-d')) }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CID</label>
                            <label class="input"><input type="text" name="cid" maxlength="45" class="input_login" value="{{ old('cid', $row->cid ?? '') }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Dias atestado</label>
                            <label class="input"><input type="number" name="qtde_dias_atestado" class="input_login" value="{{ old('qtde_dias_atestado', $row->qtde_dias_atestado ?? '') }}"></label>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Hospital/Clínica</label>
                            <label class="input"><input type="text" name="hospital_clinica" maxlength="45" class="input_login" value="{{ old('hospital_clinica', $row->hospital_clinica ?? '') }}"></label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Profissional</label>
                            <label class="input"><input type="text" name="profissional" maxlength="45" class="input_login" value="{{ old('profissional', $row->profissional ?? '') }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CRM</label>
                            <label class="input"><input type="text" name="num_crm" maxlength="45" class="input_login" value="{{ old('num_crm', $row->num_crm ?? '') }}"></label>
                        </section>
                    </div>
                    <section>
                        <label class="label">Situação</label>
                        <label class="input"><input type="text" name="situacao" maxlength="150" class="input_login" value="{{ old('situacao', $row->situacao ?? '') }}"></label>
                    </section>
                    <section>
                        <label class="label">Observação</label>
                        <label class="textarea"><textarea name="observacao" rows="3" class="input_login">{{ old('observacao', $row->observacao ?? '') }}</textarea></label>
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
                    <a class="btn btn-default" href="{{ route('admin.absenteismo.index') }}">Cancelar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
