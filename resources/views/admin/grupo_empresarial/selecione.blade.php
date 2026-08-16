@extends('layouts.admin')

@section('title', 'Selecione um cliente')

@section('content')
<div id="content">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="well no-padding smart-form client-form">
                <header style="background-color: #d6d6d6; font-weight: bold;">
                    Selecione um cliente
                </header>

                <form method="POST" action="{{ route('admin.grupo_empresarial.selecione') }}" id="selecione-form">
                    @csrf
                    <fieldset>
                        <div class="row" style="margin-bottom:200px">
                            <section class="col col-6">
                                <label class="Bold" style="margin-bottom:5px;"><strong>Cliente: </strong></label>
                                <label class="select">
                                    <select style="border-radius:6px;" name="cliente_id" id="select_cliente_id"
                                            class="select2 select_cliente_id input_login">
                                        <option value="" @selected(! $cliente_id)>Selecione...</option>
                                        @foreach ($selectClienteNew as $geId => $clienteGrupoArr)
                                            @php $geNome = $clienteGrupoArr[0]['ge_nome'] ?? ('GE #'.$geId); @endphp
                                            <optgroup label="{{ $geNome }}">
                                                @foreach ($clienteGrupoArr as $clienteGrupo)
                                                    @php
                                                        $style = '';
                                                        if ((int) $clienteGrupo['cliente_status'] === 0) {
                                                            $style = 'color:black; background-color:yellow;';
                                                        } elseif ((int) $clienteGrupo['cliente_status'] === 2) {
                                                            $style = 'background-color:#f5b8b8;';
                                                        }
                                                    @endphp
                                                    <option value="{{ $clienteGrupo['cliente_id'] }}"
                                                            style="{{ $style }} margin-left:6px;"
                                                            @selected((string) $cliente_id === (string) $clienteGrupo['cliente_id'])>
                                                        {{ $clienteGrupo['cliente_nome'] }}
                                                    </option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                        </div>
                    </fieldset>
                    <footer>
                        <button type="submit" class="btn btn-primary">Confirmar</button>
                        <a href="{{ route('admin.home') }}" class="btn btn-default">Cancelar</a>
                    </footer>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
