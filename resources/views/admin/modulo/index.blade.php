@extends('layouts.admin')

@section('title', 'Módulos')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-13"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todos Módulos</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="modulo-search-form"
                          method="GET"
                          action="{{ route('admin.modulo.index') }}"
                          class="smart-form client-form form_ajax">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <select name="modulo_id" class="input_login">
                                        @foreach ($moduloArr as $mid => $mlabel)
                                            <option value="{{ $mid }}" @selected((string) ($search['modulo_id'] ?? '') === (string) $mid)>{{ $mlabel }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="controller" value="{{ $search['controller'] ?? '' }}" placeholder="Controller" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <select name="status" class="input_login">
                                        @foreach ($statusArr as $sid => $slabel)
                                            <option value="{{ $sid }}" @selected((string) ($search['status'] ?? '') === (string) $sid)>{{ $slabel }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                            <section class="col col-2">
                                @php $hasFilter = collect($search)->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty(); @endphp
                                @if ($hasFilter)
                                    <a href="{{ route('admin.modulo.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="widget-body" style="padding: 0 15px 15px;">
                        <p style="margin-top:10px;">
                            @if ($permissao >= 2)
                                <a class="btn btn-success btn-sm" href="{{ route('admin.modulo.add') }}">
                                    <i class="fa fa-plus"></i> Novo
                                </a>
                            @endif
                        </p>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5">ID</th>
                                <th>Módulo Pai</th>
                                <th>Nome</th>
                                <th>Ordenação</th>
                                <th>Controller</th>
                                <th>Ícone</th>
                                <th>Data de Cadastro</th>
                                <th>Status</th>
                                <th class="actions" width="80">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($modulos as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->parent->nome ?? '-' }}</td>
                                    <td>{{ $row->nome }}</td>
                                    <td>{{ $row->order }}</td>
                                    <td>{{ $row->controller }}</td>
                                    <td>
                                        @if ($row->icon)
                                            <i class="fa {{ $row->icon }}"></i> &nbsp; (<i>{{ $row->icon }}</i>)
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>{{ (int) $row->status === 1 ? 'Ativo' : ((int) $row->status === 2 ? 'Excluído' : 'Inativo') }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin.modulo.view', $row->id) }}" class="btn btn-xs btn-default" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($permissao >= 2)
                                            <a href="{{ route('admin.modulo.add', $row->id) }}" class="btn btn-xs btn-primary" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center">Nenhum módulo encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{ $modulos->links() }}
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
