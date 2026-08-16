@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_critico.index') }}">MH Crítico</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="mh_critico-form"
                  method="POST"
                  action="{{ route('admin.mh_critico.add', $row?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de MH Crítico
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

                    @php
                        $principalVal = old('principal', $row->principal ?? '');
                        $listPrincipal = $row ? $listPrestadorAll : $listPrestadorSemUsados;
                    @endphp

                    <div class="row">
                        <section class="col col-2">
                            <label class="label">Prestador Principal? <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="principal" class="input_login prestador_principal" required>
                                    <option value="">Selecione...</option>
                                    <option value="0" @selected((string) $principalVal === '0')>Não</option>
                                    <option value="1" @selected((string) $principalVal === '1')>Sim</option>
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <div class="row list_prestador" style="{{ (string) $principalVal === '1' ? '' : 'display:none;' }}">
                        <section class="col col-4">
                            <label class="label">Prestador Principal<span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="mh_prestador_principal_id" class="input_login" id="mh_prestador_principal_sim">
                                    <option value="">Selecione...</option>
                                    @foreach ($listPrincipal as $pid => $pnome)
                                        <option value="{{ $pid }}" @selected((string) old('mh_prestador_principal_id', $row->mh_prestador_principal_id ?? '') === (string) $pid)>{{ $pnome }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

                    <div class="row list_prestador_opcoes" style="{{ (string) $principalVal === '0' ? '' : 'display:none;' }}">
                        <section class="col col-4">
                            <label class="label">Prestador Principal<span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="mh_prestador_principal_id" class="input_login" id="mh_prestador_principal_nao" disabled>
                                    <option value="">Selecione...</option>
                                    @foreach ($listPrincipal as $pid => $pnome)
                                        <option value="{{ $pid }}" @selected((string) old('mh_prestador_principal_id', $row->mh_prestador_principal_id ?? '') === (string) $pid)>{{ $pnome }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Prestador Opção<span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="mh_prestador_id" class="input_login">
                                    <option value="">Selecione...</option>
                                    @foreach (($row ? $listPrestadorAll : $listPrestadorSemUsados) as $pid => $pnome)
                                        <option value="{{ $pid }}" @selected((string) old('mh_prestador_id', $row->mh_prestador_id ?? '') === (string) $pid)>{{ $pnome }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Opção</label>
                            <label class="select">
                                <select name="opcao" class="input_login">
                                    @foreach ($ArrOpcao as $k => $v)
                                        <option value="{{ $k }}" @selected((string) old('opcao', $row->opcao ?? '') === (string) $k)>{{ $v }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>

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

@push('scripts')
<script>
$(document).ready(function () {
    function syncPrincipal() {
        var valor = $('.prestador_principal').val();
        if (valor == '1') {
            $('.list_prestador').show();
            $('.list_prestador_opcoes').hide();
            $('#mh_prestador_principal_sim').prop('disabled', false);
            $('#mh_prestador_principal_nao').prop('disabled', true);
        } else if (valor == '0') {
            $('.list_prestador_opcoes').show();
            $('.list_prestador').hide();
            $('#mh_prestador_principal_sim').prop('disabled', true);
            $('#mh_prestador_principal_nao').prop('disabled', false);
        } else {
            $('.list_prestador').hide();
            $('.list_prestador_opcoes').hide();
        }
    }
    $('.prestador_principal').change(syncPrincipal);
    syncPrincipal();
});
</script>
@endpush
