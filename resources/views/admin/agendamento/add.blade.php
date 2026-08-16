@extends('layouts.admin')
@section('title', $title)
@section('breadcrumb')
    <li><a href="{{ route('admin.agendamento.index') }}">Agendamentos</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection
@section('content')
<style>.campo_obrigatorio { color: #c00; }</style>
<div class="row">
    <div class="col-xs-12">
        <div class="well no-padding">
            <form method="POST" action="{{ route('admin.agendamento.add', $row?->id) }}" class="smart-form client-form" id="agendamento-form">
                @csrf
                @if ($row)<input type="hidden" name="id" value="{{ $row->id }}">@endif
                <header>{{ $row ? 'Edição' : 'Cadastro' }} de Agendamento</header>
                <fieldset>
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul style="margin:0;padding-left:18px;">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                    @endif
                    <section>
                        <label class="label">Atendimento <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="atendimento_id" class="input_login" required>
                                <option value="">Selecione...</option>
                                @foreach (($atendimentos ?? []) as $aid => $alabel)
                                    <option value="{{ $aid }}" @selected((string) old('atendimento_id', $row->atendimento_id ?? '') === (string) $aid)>{{ $alabel }}</option>
                                @endforeach
                            </select><i></i>
                        </label>
                    </section>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Usuário <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="usuario_id" class="input_login" required>
                                    <option value="">Selecione...</option>
                                    @foreach (($usuarios ?? []) as $uid => $ulabel)
                                        <option value="{{ $uid }}" @selected((string) old('usuario_id', $row->usuario_id ?? '') === (string) $uid)>{{ $ulabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-4">
                            <label class="label">Agendado para <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="usuario_agendamento_id" class="input_login" required>
                                    <option value="">Selecione...</option>
                                    @foreach (($usuarios ?? []) as $uid => $ulabel)
                                        <option value="{{ $uid }}" @selected((string) old('usuario_agendamento_id', $row->usuario_agendamento_id ?? '') === (string) $uid)>{{ $ulabel }}</option>
                                    @endforeach
                                </select><i></i>
                            </label>
                        </section>
                        <section class="col col-3">
                            <label class="label">Data/Hora</label>
                            <label class="input"><input type="datetime-local" name="data_hora" class="input_login" value="{{ old('data_hora', optional($row?->data_hora)->format('Y-m-d\\TH:i')) }}"></label>
                        </section>
                    </div>
                    <section>
                        <label class="label">Descrição</label>
                        <label class="textarea"><textarea name="descricao" rows="3" class="input_login">{{ old('descricao', $row->descricao ?? '') }}</textarea></label>
                    </section>
                    <section>
                        <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                        <label class="select">
                            <select name="status" class="input_login" required>
                                <option value="1" @selected((string) old('status', $row->status ?? '1') === '1')>Ativo</option>
                                <option value="0" @selected((string) old('status', $row->status ?? '1') === '0')>Inativo</option>
                            </select><i></i>
                        </label>
                    </section>
                </fieldset>
                <footer>
                    <button type="submit" class="btn btn-primary">Salvar</button>
                    <a class="btn btn-default" href="{{ route('admin.agendamento.index') }}">Cancelar</a>
                </footer>
            </form>
        </div>
    </div>
</div>
@endsection
