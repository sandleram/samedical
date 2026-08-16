@extends('layouts.admin')
@section('title', 'Absenteísmo')
@section('content')
<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-absenteismo" data-widget-colorbutton="false" data-widget-editbutton="false" data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header><span class="widget-icon"><i class="fa fa-list"></i></span><h2>Todos Absenteísmo</h2></header>
                <div class="row" style="padding: 10px 15px 0;">
                    <form method="GET" action="{{ route('admin.absenteismo.index') }}" class="smart-form client-form" id="absenteismo-search-form">
                        <div class="row">
                            <section class="col col-1"><label class="input"><input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login"></label></section>
                            <section class="col col-2"><label class="input"><input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Nome" class="input_login"></label></section>
                            <section class="col col-1"><label class="input"><input type="text" name="cid" value="{{ $search['cid'] ?? '' }}" placeholder="CID" class="input_login"></label></section>
                            <section class="col col-1">
                                <label class="select"><select name="status" class="input_login"><option value="">Status...</option><option value="1" @selected((string)($search['status']??'')==='1')>Ativo</option><option value="0" @selected((string)($search['status']??'')==='0')>Inativo</option></select><i></i></label>
                            </section>
                            <section class="col col-1"><button type="submit" class="btn btn-primary" style="padding:4px 10px;">Filtrar</button></section>
                        </div>
                    </form>
                </div>
                <div class="table-responsive" style="padding:0 15px 15px;">
                    <p style="margin-top:10px;">@if($permissao>=2)<a class="btn btn-success btn-sm" href="{{ route('admin.absenteismo.add') }}"><i class="fa fa-plus"></i> Novo</a>@endif</p>
                    <table class="table table-bordered table-striped">
                        <thead><tr><th>ID</th><th>Beneficiário</th><th>CID</th><th>Saída</th><th>Retorno</th><th>Status</th><th class="actions">Ações</th></tr></thead>
                        <tbody>
                        @forelse($rows as $row)
                            <tr>
                                <td>{{ $row->id }}</td>
                                <td>{{ $row->beneficiario->nome ?? '-' }}</td>
                                <td>{{ $row->cid }}</td>
                                <td>{{ optional($row->data_saida)->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ optional($row->data_retorno)->format('d/m/Y') ?? '-' }}</td>
                                <td>{{ (int)$row->status===1?'Ativo':'Inativo' }}</td>
                                <td class="actions">
                                    <a href="{{ route('admin.absenteismo.view',$row->id) }}" class="btn btn-xs btn-default"><i class="fa fa-eye"></i></a>
                                    @if($permissao>=2)<a href="{{ route('admin.absenteismo.add',$row->id) }}" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a>@endif
                                </td>
                            </tr>
                        @empty<tr><td colspan="7" class="text-center">Nenhum registro encontrado.</td></tr>@endforelse
                        </tbody>
                    </table>
                    <div style="padding:10px 0;">{{ $rows->links() }}</div>
                </div>
            </div>
        </article>
    </div>
</section>
@endsection
