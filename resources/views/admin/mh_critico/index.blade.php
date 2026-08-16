@extends('layouts.admin')

@section('title', 'MH Crítico')

@section('content')
<style>
    .tree li.parent_li>span:hover {
        background-color: #4c96ed;
        border: 1px solid #5453fb;
        color: #fff;
    }
    .tree li.parent_li>span:hover+ul li::before { border-left-color: #4c96ed; }
    .tree li.parent_li>span:hover+ul li::after { border-top-color: #4c96ed; }
    .tree li.parent_li>span:hover+ul li span {
        background: #d6edff!important;
        border: 1px solid #8f8f8f;
        color: #000;
    }
</style>

<section id="widget-grid" class="">
    <div class="row">
        <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div class="jarviswidget jarviswidget-color-blue" id="wid-id-12"
                 data-widget-colorbutton="false" data-widget-editbutton="false"
                 data-widget-deletebutton="false" data-widget-togglebutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-list"></i></span>
                    <h2>Todas MH Crítico</h2>
                </header>

                <div class="row" style="padding: 10px 15px 0;">
                    <form id="mh_critico-search-form"
                          method="GET"
                          action="{{ route('admin.mh_critico.index') }}"
                          class="smart-form client-form">
                        <div class="row">
                            <section class="col col-1">
                                <label class="input">
                                    <input type="text" name="id_" value="{{ $search['id_'] ?? '' }}" placeholder="ID" class="input_login">
                                </label>
                            </section>
                            <section class="col col-4">
                                <label class="input">
                                    <input type="text" name="nome" value="{{ $search['nome'] ?? '' }}" placeholder="Prestador Principal" class="input_login">
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
                                    <a href="{{ route('admin.mh_critico.index') }}">Limpar Filtros</a>
                                @endif
                            </section>
                        </div>
                    </form>
                </div>

                <div>
                    <div class="widget-body" style="padding: 0 15px 15px;">
                        <p style="margin-top:10px;">
                            @if ($permissao >= 2)
                                <a class="btn btn-success btn-sm" href="{{ route('admin.mh_critico.add') }}">
                                    <i class="fa fa-plus"></i> Novo
                                </a>
                            @endif
                        </p>

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Prestado Princiapal</th>
                                <th>SubPrestadores</th>
                                <th>Ciclo</th>
                                <th>Status Ciclo</th>
                                <th>Data de Cadastro</th>
                                <th>Status</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($rows as $row)
                                @php
                                    $totalHist = $row->historicos->count();
                                    $histLink = route('admin.mh_critico_historico.index', ['mh_critico_id' => $row->id]);
                                    $subs = $rowsSub[$row->mh_prestador_principal_id] ?? ($rowsSub[$row->mh_prestador_id] ?? []);
                                    $hasSubs = count($subs) > 0;
                                @endphp
                                <tr>
                                    <td>
                                        <div class="tree" style="font-size:9px;">
                                            <ul style="margin-bottom:0 !important;">
                                                <li>
                                                    @if ($hasSubs)
                                                        <span style="padding-left:20px; min-width:260px;">
                                                            <i class="fa fa-lg fa-plus-circle" style="float:left;margin-left:-15px;"></i>
                                                            <b>{{ $row->prestador->nome ?? '-' }}</b><br>
                                                            <b>Cidade:</b> {{ $row->prestador->cidade ?? '' }} &nbsp;&nbsp;
                                                            <b>Estado:</b> {{ $row->prestador->estado ?? '' }}<br><br>
                                                            <a href="{{ $histLink }}">Histórico ({{ $totalHist }})</a>
                                                        </span>
                                                        <ul>
                                                            @foreach ($subs as $rowSub)
                                                                @php
                                                                    $subHist = $rowSub->historicos->count();
                                                                    $subLink = route('admin.mh_critico_historico.index', ['mh_critico_id' => $rowSub->id]);
                                                                @endphp
                                                                <li style="display:none">
                                                                    <span style="min-width:260px;">
                                                                        <b>Opção:</b> {{ $rowSub->opcao }} <br>
                                                                        <b>{{ $rowSub->prestador->nome ?? '-' }}</b><br>
                                                                        <b>Ciclo:</b> {{ $ArrCiclo[$rowSub->ciclo] ?? $rowSub->ciclo }} &nbsp;&nbsp;
                                                                        <b>Cidade:</b> {{ $rowSub->prestador->cidade ?? '' }} &nbsp;&nbsp;
                                                                        <b>Estado:</b> {{ $rowSub->prestador->estado ?? '' }}<br><br>
                                                                        <a href="{{ $subLink }}">Histórico ({{ $subHist }})</a>
                                                                    </span>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @else
                                                        <span style="min-width:260px;">
                                                            <b>{{ $row->prestador->nome ?? '-' }}</b><br>
                                                            <b>Cidade:</b> {{ $row->prestador->cidade ?? '' }} &nbsp;&nbsp;
                                                            <b>Estado:</b> {{ $row->prestador->estado ?? '' }}<br><br>
                                                            <a href="{{ $histLink }}">Histórico ({{ $totalHist }})</a>
                                                        </span>
                                                    @endif
                                                </li>
                                            </ul>
                                        </div>
                                    </td>
                                    <td>{{ count($subs) }}</td>
                                    <td>{{ $ArrCiclo[$row->ciclo] ?? $row->ciclo }}</td>
                                    <td>{{ $ArrStatusCiclo[$row->status_ciclo] ?? $row->status_ciclo }}</td>
                                    <td>{{ optional($row->data_cadastro)->format('d/m/Y H:i') }}</td>
                                    <td>{{ (int) $row->status === 1 ? 'Ativo' : 'Inativo' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center">Nenhum registro encontrado.</td>
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

@push('scripts')
<script>
$(document).ready(function () {
    $('.tree > ul').attr('role', 'tree').find('ul').attr('role', 'group');
    $('.tree').find('li:has(ul)').addClass('parent_li').attr('role', 'treeitem').find(' > span').attr('title', 'Collapse this branch').on('click', function (e) {
        var children = $(this).parent('li.parent_li').find(' > ul > li');
        if (children.is(':visible')) {
            children.hide('fast');
            $(this).attr('title', 'Expand this branch').find(' > i').removeClass().addClass('fa fa-lg fa-plus-circle');
        } else {
            children.show('fast');
            $(this).attr('title', 'Collapse this branch').find(' > i').removeClass().addClass('fa fa-lg fa-minus-circle');
        }
        e.stopPropagation();
    });
});
</script>
@endpush
