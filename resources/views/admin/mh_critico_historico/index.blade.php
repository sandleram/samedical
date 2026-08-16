@extends('layouts.admin')

@section('title', 'MH Crítico Histórico')

@section('breadcrumb')
    <li><a href="{{ route('admin.mh_critico.index') }}">MH Crítico</a></li>
    <li>Histórico #{{ $mh_critico_id }}</li>
@endsection

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-12"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Histórico — {{ $critico->prestador->nome ?? ('Crítico #'.$mh_critico_id) }}</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="mh_critico_historico-search-form"
                          method="GET"
                          action="{{ route('admin.mh_critico_historico.index', $mh_critico_id) }}"
                          class="smart-form client-form">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <select name="status" class="input_login">
                                        <option value="">Status...</option>
                                        <option value="1" @selected((string) ($search['status'] ?? '') === '1')>Ativo</option>
                                        <option value="0" @selected((string) ($search['status'] ?? '') === '0')>Inativo</option>
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                            <section class="col col-2">
                                @if (collect($search)->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty())
                                    <a href="{{ route('admin.mh_critico_historico.index', $mh_critico_id) }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="widget-body" style="padding: 0 15px 15px;">
                        <p style="margin-top:10px;">
                            @if ($permissao >= 2)
                                <a class="btn btn-success btn-sm" href="{{ route('admin.mh_critico_historico.add', $mh_critico_id) }}">
                                    <i class="fa fa-plus"></i> Novo
                                </a>
                            @endif
                            <a class="btn btn-default btn-sm" href="{{ route('admin.mh_critico.index') }}">
                                <i class="fa fa-arrow-left"></i> Voltar
                            </a>
                        </p>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5">ID</th>
                                <th>Prestador</th>
                                <th>Cidade - Estado</th>
                                <th>Ciclo</th>
                                <th>Status Ciclo</th>
                                <th>Data de Cadastro</th>
                                <th>Status</th>
                                <th class="actions">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->critico->prestador->nome ?? '-' }}</td>
                                    <td>{{ ($row->critico->prestador->cidade ?? '') }} - {{ ($row->critico->prestador->estado ?? '') }}</td>
                                    <td>{{ $ArrCiclo[$row->ciclo] ?? $row->ciclo }}</td>
                                    <td>{{ $ArrStatusCiclo[$row->status_ciclo] ?? $row->status_ciclo }}</td>
                                    <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') }}</td>
                                    <td>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin.mh_critico_historico.view', [$mh_critico_id, $row->id]) }}" class="btn btn-xs btn-default" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($permissao >= 2)
                                            <a href="{{ route('admin.mh_critico_historico.add', [$mh_critico_id, $row->id]) }}" class="btn btn-xs btn-primary" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum histórico encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{ $rows->links() }}
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
