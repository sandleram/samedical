@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.perfil.index') }}">Perfis</a></li>
    <li>{{ $perfil ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="perfil-form"
                  method="POST"
                  action="{{ route('admin.perfil.add', $perfil?->id) }}"
                  class="smart-form client-form">
                @csrf
                @if ($perfil)
                    <input type="hidden" name="id" value="{{ $perfil->id }}">
                @endif

                <header>
                    {{ $perfil ? 'Edição' : 'Cadastro' }} de Perfil
                    @if ($perfil)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.perfil.view', $perfil->id) }}" style="margin-top:-4px;">
                            <i class="fa fa-eye"></i> Ver
                        </a>
                    @endif
                </header>

                <fieldset>
                    @if ($perfil)
                        <div class="row">
                            <section class="col col-6">
                                <label class="label"><strong>ID:</strong> {{ $perfil->id }}</label>
                            </section>
                        </div>
                    @endif

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="nome" maxlength="100" class="input_login"
                                   value="{{ old('nome', $perfil->nome ?? '') }}" required>
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Tipo <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="tipo" class="input_login" required>
                                    @foreach ($tipoArr as $tid => $tlabel)
                                        <option value="{{ $tid }}" @selected((string) old('tipo', $perfil->tipo ?? '') === (string) $tid)>{{ $tlabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                            <span class="note">
                                0 = Operacional <i>(tem acessos médicos)</i><br>
                                1 = Administrativo <i>(Não tem acessos médicos)</i><br>
                                2 = Master (Acessa Tudo)
                            </span>
                        </section>
                    </div>
                    <div class="row">
                        <section class="col col-4">
                            <label class="label">Status <span class="campo_obrigatorio">*</span></label>
                            <label class="select">
                                <select name="status" class="input_login" required>
                                    @foreach ($statusArr as $sid => $slabel)
                                        <option value="{{ $sid }}" @selected((string) old('status', $perfil->status ?? '1') === (string) $sid)>{{ $slabel }}</option>
                                    @endforeach
                                </select>
                                <i></i>
                            </label>
                        </section>
                    </div>
                </fieldset>

                <fieldset>
                    <header>
                        <h3>Gerenciamento de Acessos</h3>
                    </header>

                    <table class="table table-bordered">
                        <thead>
                        <tr>
                            <th>Módulos</th>
                            <th width="100" style="text-align: center;"><i class="fa fa-ban txt-color-teal"></i> Sem Acesso</th>
                            <th width="80" style="text-align: center;"><i class="fa fa-eye txt-color-teal"></i> Visualizar</th>
                            <th width="130" style="text-align: center;"><i class="fa fa-edit txt-color-teal"></i> Adicionar / Editar</th>
                            <th width="80" style="text-align: center;"><i class="fa fa-gears txt-color-teal"></i> Gerenciar</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($modulos->where('modulo_id', 0) as $pai)
                            @include('admin.perfil._perm_row', [
                                'mod' => $pai,
                                'permissoesSalvas' => $permissoesSalvas,
                                'style' => 'background-color:#aaa !important; color:#fff;',
                                'prefix' => '',
                            ])
                            @foreach ($modulos->where('modulo_id', $pai->id) as $filho)
                                @include('admin.perfil._perm_row', [
                                    'mod' => $filho,
                                    'permissoesSalvas' => $permissoesSalvas,
                                    'style' => '',
                                    'prefix' => '&nbsp;&nbsp;<span style="font-size:16px;">&bull;</span> ',
                                ])
                                @foreach ($modulos->where('modulo_id', $filho->id) as $neto)
                                    @include('admin.perfil._perm_row', [
                                        'mod' => $neto,
                                        'permissoesSalvas' => $permissoesSalvas,
                                        'style' => '',
                                        'prefix' => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span style="font-size:14px;">&ordm;</span> &nbsp;',
                                    ])
                                @endforeach
                            @endforeach
                        @endforeach
                        </tbody>
                    </table>
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
