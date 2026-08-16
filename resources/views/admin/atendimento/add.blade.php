@extends('layouts.admin')
@section('title', $title)
@section('breadcrumb')
    <li><a href="{{ route('admin.atendimento.index') }}">Atendimentos</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection
@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form method="POST" action="{{ route('admin.atendimento.add', $row?->id) }}" class="smart-form client-form" id="atendimento-form">
                @csrf
                @if ($row)<input type="hidden" name="id" value="{{ $row->id }}">@endif
                <header>{{ $row ? 'Edição' : 'Cadastro' }} de Atendimento</header>
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
                    <div class="row">
                        <section class="col col-3">
                            <label class="label">Tipo <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="tipo_atendimento" class="input_login" required>
                                    @foreach (($tipoAtendimentoArr ?? ['' => 'Selecione...']) as $tid => $tlabel)
                                        <option value="{{ $tid }}" @selected((string) old('tipo_atendimento', $row->tipo_atendimento ?? '') === (string) $tid)>{{ $tlabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-2">
                            <label class="label">CID</label>
                            <label class="input"><input type="text" name="cid" maxlength="6" class="input_login" value="{{ old('cid', $row->cid ?? '') }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Forma</label>
                            <label class="input"><input type="number" name="forma_atendimento" class="input_login" value="{{ old('forma_atendimento', $row->forma_atendimento ?? '') }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Status atendimento <span class="campo_obrigatorio">*</span></label>
                            <label class="input"><input type="number" name="status_atendimento" class="input_login" required value="{{ old('status_atendimento', $row->status_atendimento ?? '1') }}"></label>
                        </section>
                        <section class="col col-2">
                            <label class="label">Conclusão</label>
                            <label class="input"><input type="date" name="data_conclusao" class="input_login" value="{{ old('data_conclusao', optional($row?->data_conclusao)->format('Y-m-d')) }}"></label>
                        </section>
                    </div>
                    <section>
                        <label class="label">Descrição</label>
                        <label class="textarea"><textarea name="descricao" rows="4" class="input_login">{{ old('descricao', $row->descricao ?? '') }}</textarea></label>
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
                    <a class="btn btn-default" href="{{ route('admin.atendimento.index') }}">Cancelar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
