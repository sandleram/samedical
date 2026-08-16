@extends('layouts.admin')

@section('title', 'Beneficiários')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-12"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todos Beneficiários</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="beneficiario-search-form"
                          method="GET"
                          action="{{ route('admin.beneficiario.index') }}"
                          class="smart-form client-form">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="cpf" value="{{ $search['cpf'] ?? '' }}" placeholder="CPF" class="cpf_mask input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login">
                                </label>
                            </section>
                            <section class="col col-2">
                                <label class="input">
                                    <input type="text" name="nome_social" value="{{ $search['nome_social'] ?? '' }}" placeholder="Nome Social" class="input_login">
                                </label>
                            </section>
                            <section class="col col-1">
                                <label class="select">
                                    <select name="situacao" class="input_login">
                                        <option value="">Situação...</option>
                                        <option value="Ativo" @selected(($search['situacao'] ?? '') === 'Ativo')>Ativo</option>
                                        <option value="Inativo" @selected(($search['situacao'] ?? '') === 'Inativo')>Inativo</option>
                                    </select>
                                    <i></i>
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
                                @php
                                    $hasFilter = collect($search)->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty();
                                @endphp
                                @if ($hasFilter)
                                    <a href="{{ route('admin.beneficiario.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="table-responsive" style="padding: 0 15px 15px;">
                        <p style="margin-top:10px;">
                            @if ($permissao >= 2)
                                <a class="btn btn-success btn-sm" href="{{ route('admin.beneficiario.add') }}">
                                    <i class="fa fa-plus"></i> Novo
                                </a>
                            @endif
                        </p>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th width="5">ID</th>
                                <th width="110">CPF</th>
                                <th>Nome</th>
                                <th>Nome Social</th>
                                <th>Cliente</th>
                                <th>Situação</th>
                                @if (in_array($perfil_id, $perfil_adm, true) || $permissao === 3)
                                    <th width="20">Status</th>
                                    <th class="actions" width="80">Ações</th>
                                @else
                                    <th class="actions" width="60">Ações</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($beneficiarios as $row)
                                <tr>
                                    <td>{{ $row->id }}</td>
                                    <td>{{ $row->cpf }}</td>
                                    <td>{{ $row->nome }}</td>
                                    <td>{{ $row->nome_social }}</td>
                                    <td>{{ $row->cliente->nome ?? '-' }}</td>
                                    <td>{{ $row->situacao }}</td>
                                    @if (in_array($perfil_id, $perfil_adm, true) || $permissao === 3)
                                        <td>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</td>
                                    @endif
                                    <td class="actions">
                                        <a href="{{ route('admin.beneficiario.view', $row->id) }}" class="btn btn-xs btn-default" title="Ver">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        @if ($permissao >= 2)
                                            <a href="{{ route('admin.beneficiario.add', $row->id) }}" class="btn btn-xs btn-primary" title="Editar">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">Nenhum beneficiário encontrado.</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>

                        {{ $beneficiarios->links() }}
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
