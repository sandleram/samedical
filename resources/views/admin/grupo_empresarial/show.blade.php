@extends('layouts.admin')

@section('title', 'Grupo Empresarial')

@section('breadcrumb')
    <li><a href="{{ route('admin.grupo_empresarial.index') }}">Grupos Empresariais</a></li>
    <li>{{ $row->nome }}</li>
@endsection

@section('content')
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="well no-padding smart-form client-form">
            <header>
                Visualização de Grupo Empresarial
                <span class="pull-right" style="margin-top:-4px;">
                    @if ($permissao >= 2)
                        <a class="btn btn-primary btn-xs" href="{{ route('admin.grupo_empresarial.add', $row->id) }}">
                            <i class="fa fa-edit"></i> Editar
                        </a>
                    @endif
                    <a class="btn btn-default btn-xs" href="{{ route('admin.grupo_empresarial.index') }}">
                        <i class="fa fa-arrow-left"></i> Voltar
                    </a>
                </span>
            </header>
            <fieldset>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>ID: </strong></label>
                        <label>{{ $row->id }}</label>
                    </section>
                </div>
                @if ($row->img_logo)
                    <div class="row">
                        <section class="col col-2">
                            @php $logo = 'img/uploads/grupo_empresarial/'.$row->img_logo; @endphp
                            @if (is_file(public_path($logo)))
                                <img src="{{ asset($logo) }}" alt="" width="36" class="link_image">
                            @endif
                        </section>
                    </div>
                @endif
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>Nome: </strong></label>
                        <label>{{ $row->nome }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-12">
                        <label class="Bold"><strong>BI Gerencial: </strong></label>
                        <label>
                            @if ($row->bi)
                                <a href="{{ $row->bi }}" target="_blank" rel="noopener">{{ $row->bi }}</a>
                            @else
                                -
                            @endif
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Cor: </strong></label>
                        <label>
                            @if ($row->cor)
                                <span style="display:inline-block;width:16px;height:16px;background:{{ $row->cor }};vertical-align:middle;"></span>
                                {{ $row->cor }}
                            @else
                                -
                            @endif
                        </label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Data de Cadastro: </strong></label>
                        <label>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</label>
                    </section>
                </div>
                <div class="row">
                    <section class="col col-4">
                        <label class="Bold"><strong>Status: </strong></label>
                        <label>{{ (int) $row->status === 1 ? 'Ativo' : ((int) $row->status === 2 ? 'Excluído' : 'Inativo') }}</label>
                    </section>
                </div>
            </fieldset>
        </div>
    </div>
</div>
@endsection
