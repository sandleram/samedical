@extends('layouts.admin')

@section('title', 'Benefícios')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-beneficio"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todos Benefícios</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="beneficio-search-form"
                          method="GET"
                          action="{{ route('admin.beneficio.index') }}"
                          class="smart-form client-form form_ajax">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="descricao" value="{{ $search['descricao'] ?? '' }}" placeholder="Descricao" class="input_login">
                                </label>
                            </section>
                            <section class="col col-1">
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
                                @php $hasFilter = collect($search)->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty(); @endphp
                                @if ($hasFilter)
                                    <a href="{{ route('admin.beneficio.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="table-responsive" style="padding: 0 15px 15px;">
                        <p style="margin-top:10px;">
                            @if ($permissao >= 2)
                                <a class="btn btn-success btn-sm" href="{{ route('admin.beneficio.add') }}">
                                    <i class="fa fa-plus"></i> Novo
                                </a>
                            @endif
                        </p>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5">ID</th>
                                <th>Descrição</th>
                                <th>Operadora</th>
                                <th>Tipo</th>
                                <th>Status</th>
                                <th class="actions" width="80">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($beneficios as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->descricao }}</td>
                                    <td>{{ $row->operadora->nome ?? '-' }}</td>
                                    <td>{{ $row->tipoBeneficio->descricao ?? '-' }}</td>
                                    <td>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</td>
                                    <td class="actions">
                                        <a href="{{ route('admin.beneficio.view', $row->id) }}" class="btn btn-xs btn-default" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($permissao >= 2)
                                            <a href="{{ route('admin.beneficio.add', $row->id) }}" class="btn btn-xs btn-primary" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">Nenhum registro encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        <div style="padding:10px 0;">{{ $beneficios->links() }}</div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
