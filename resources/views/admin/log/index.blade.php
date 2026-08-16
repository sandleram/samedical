@extends('layouts.admin')

@section('title', 'Logs')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-log"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todas Logs</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="log-search-form"
                          method="GET"
                          action="{{ route('admin.log.index') }}"
                          class="smart-form client-form">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="log" value="{{ $search['log'] ?? '' }}" placeholder="Ação" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="description" value="{{ $search['description'] ?? '' }}" placeholder="Descrição" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="date" name="data_inicio" value="{{ $search['data_inicio'] ?? '' }}" class="input_login" placeholder="Data Início">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="date" name="data_fim" value="{{ $search['data_fim'] ?? '' }}" class="input_login" placeholder="Data Fim">
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                            <section class="col col-2">
                                @php
                                    $hasFilter = collect($search)->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty();
                                @endphp
                                @if ($hasFilter)
                                    <a href="{{ route('admin.log.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="table-responsive" style="padding: 0 15px 15px;">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5">ID</th>
                                <th>Ação</th>
                                <th>Usuário</th>
                                <th style="width: 200px;">Mensagem</th>
                                <th style="width: 200px;">Description</th>
                                <th style="width: 200px;">Server Description</th>
                                <th>Data de Cadastro</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($rows as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->log }}</td>
                                    <td>{{ $row->usuario->nome ?? '-' }}</td>
                                    <td><span class="note" style="word-wrap: break-word; width: 200px;">{{ $row->mensagem }}</span></td>
                                    <td><div class="note" style="word-wrap: break-word; width: 200px;">{{ $row->description }}</div></td>
                                    <td><span class="note" style="word-wrap: break-word; width: 200px;">{{ $row->server_description }}</span></td>
                                    <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">Nenhum log encontrado.</td>
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
