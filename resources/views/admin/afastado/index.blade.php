@extends('layouts.admin')

@section('title', 'Afastados')

@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-afastado"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todos Afastados</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form method="GET" action="{{ route('admin.afastado.index') }}" class="smart-form client-form" id="afastado-search-form">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input"><input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login"></label>
                            </section>
                            <section class="col col-2">
                                <label class="input"><input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login"></label>
                            </section>
                            <section class="col col-2">
                                <label class="input"><input type="text" name="cpf" value="{{ $search['cpf'] ?? '' }}" placeholder="CPF" class="cpf_mask input_login"></label>
                            </section>
                            <section class="col col-1">
                                <label class="select">
                                    <select name="status" class="input_login">
                                        <option value="">Status...</option>
                                        <option value="1" @selected((string) ($search['status'] ?? '') === '1')>Ativo</option>
                                        <option value="0" @selected((string) ($search['status'] ?? '') === '0')>Inativo</option>
                                    </select><i></i>
                                </label>
                            </section>
                            <section class="col col-1">
                                <button type="submit" class="btn btn-primary" style="padding: 4px 10px;">Filtrar</button>
                            </section>
                        </div>
                    </form>
                </div>

                <div class="table-responsive" style="padding: 0 15px 15px;">
                    <p style="margin-top:10px;">
                        @if ($permissao >= 2)
                            <a class="btn btn-success btn-sm" href="{{ route('admin.afastado.add') }}"><i class="fa fa-plus"></i> Novo</a>
                        @endif
                    </p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th width="5">ID</th>
                            <th>Beneficiário</th>
                            <th>CID</th>
                            <th>Início</th>
                            <th>Situação</th>
                            <th>Status</th>
                            <th class="actions" width="80">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse ($afastados as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->beneficiario->nome ?? '-' }}</td>
                                <td>{{ $row->cid }}</td>
                                <td>{{ optional($row->data_inicio_afastamento)->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ $row->situacao === 'RT' ? 'Retorno' : 'Afastado' }}</td>
                                <td>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.afastado.view', $row->id) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                    @if ($permissao >= 2)
                                        <a href="{{ route('admin.afastado.add', $row->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="7" class="text-center">Nenhum registro encontrado.</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                    <div style="padding:10px 0;">{{ $afastados->links() }}</div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
