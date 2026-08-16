@extends('layouts.admin')

@section('title', 'Usuários')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-11"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todos Usuários</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="usuario-search-form"
                          method="GET"
                          action="{{ route('admin.usuario.index') }}"
                          class="smart-form client-form form_ajax">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="usuario" value="{{ $search['usuario'] ?? '' }}" placeholder="Usuário" class="input_login">
                                </label>
                            </section>
                            <section class="col col-3">
                                <label class="input">
                                    <input type="text" name="email_" value="{{ $search['email_'] ?? '' }}" placeholder="E-mail" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="select">
                                    <select name="perfil" class="input_login">
                                        @foreach ($perfilArr as $pid => $plabel)
                                            <option value="{{ $pid }}" @selected((string) ($search['perfil'] ?? '') === (string) $pid)>{{ $plabel }}</option>
                                        @endforeach
                                    </select>
                                    <i></i>
                                </label>
                            </section>
                        </div>
                        <div class="row">
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
                                    <a href="{{ route('admin.usuario.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="table-responsive" style="padding: 0 15px 15px;">
                        @include('partials.admin.acoes_geral', [
                            'permissao' => $permissao,
                            'addRoute' => route('admin.usuario.add'),
                            'novoLabel' => 'Novo Usuário',
                            'context' => 'index',
                        ])

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="35">ID</th>
                                @if ($isRoot)
                                    <th>G.E.</th>
                                @endif
                                <th>Perfil</th>
                                <th>Nome</th>
                                <th>Usuário</th>
                                <th>E-mail</th>
                                <th>Data de Cadastro</th>
                                <th>Status</th>
                                <th class="actions" width="80">Ações</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($usuarios as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    @if ($isRoot)
                                        <td>{{ $row->grupoEmpresarial->nome ?? '-' }}</td>
                                    @endif
                                    <td>{{ $row->perfil->nome ?? '-' }}</td>
                                    <td>{{ $row->nome }}</td>
                                    <td>{{ $row->usuario }}</td>
                                    <td>{{ $row->email }}</td>
                                    <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') ?? '-' }}</td>
                                    <td>{{ (int) $row->status === 1 ? 'Ativo' : ((int) $row->status === 2 ? 'Excluído' : 'Inativo') }}</td>
                                    <td class="actions">
                                        @include('partials.admin.acoes_lista', [
                                            'permissao' => $permissao,
                                            'viewRoute' => route('admin.usuario.view', $row->id),
                                            'editRoute' => route('admin.usuario.add', $row->id),
                                        ])
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ $isRoot ? 9 : 8 }}" class="text-center">Nenhum usuário encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{ $usuarios->links() }}
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
