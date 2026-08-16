@extends('layouts.admin')

@section('title', $title)

@section('breadcrumb')
    <li><a href="{{ route('admin.grupo_empresarial.index') }}">Grupos Empresariais</a></li>
    <li>{{ $row ? 'Edição' : 'Cadastro' }}</li>
@endsection

@section('content')
<style>
    .campo_obrigatorio { color: #c00; }
</style>

<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding">
            <form id="grupo_empresarial-form"
                  method="POST"
                  action="{{ route('admin.grupo_empresarial.add', $row?->id) }}"
                  class="smart-form client-form"
                  enctype="multipart/form-data">
                @csrf
                @if ($row)
                    <input type="hidden" name="id" value="{{ $row->id }}">
                @endif

                <header>
                    {{ $row ? 'Edição' : 'Cadastro' }} de Grupo Empresarial
                    @if ($row)
                        <a class="btn btn-default btn-xs pull-right" href="{{ route('admin.grupo_empresarial.view', $row->id) }}" style="margin-top:-4px;">
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

                    <section>
                        <label class="label">Nome <span class="campo_obrigatorio">*</span></label>
                        <label class="input">
                            <input type="text" name="nome" maxlength="60" class="input_login"
                                   value="{{ old('nome', $row->nome ?? '') }}" required>
                        </label>
                    </section>

                    <section>
                        <label class="label">BI Gerencial</label>
                        <label class="input">
                            <input type="text" name="bi" maxlength="255" class="input_login"
                                   value="{{ old('bi', $row->bi ?? '') }}" placeholder="BI">
                        </label>
                    </section>

                    <div class="row">
                        <section class="col col-1">
                            <label class="label">Cor</label>
                            <label class="input">
                                <input type="color" class="form-control form-control-color"
                                       name="cor" id="GrupoEmpresarialCor"
                                       value="{{ old('cor', $row->cor ?? '#88a284') }}"
                                       title="Escolha a cor">
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

                    @if ($row?->img_logo)
                        <input type="hidden" name="img_logo" value="{{ $row->img_logo }}">
                        <section>
                            <label class="label">Logo atual</label>
                            @php $logo = 'img/uploads/grupo_empresarial/'.$row->img_logo; @endphp
                            @if (is_file(public_path($logo)))
                                <p><img src="{{ asset($logo) }}" alt="" style="width:100px;"></p>
                            @else
                                <p class="text-muted">{{ $row->img_logo }}</p>
                            @endif
                        </section>
                    @endif
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
