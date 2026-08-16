@extends('layouts.admin')

@section('title', 'Validação de Importação')

@section('breadcrumb')
    <li><a href="{{ route('admin.importacao.index') }}">Importação</a></li>
    <li>Validação</li>
@endsection

@section('content')
<p>&nbsp;</p>
<h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;">Validação de Importação</h4>

@if (count($rows) > 0)
<section id="widget-grid" class="" style="margin-top:20px;">
    <div class="row">
        <article class="col-sm-12">
            <div class="jarviswidget jarviswidget-color-red" id="wid-id-validacao" data-widget-editbutton="false">
                <header>
                    <span class="widget-icon"><i class="fa fa-check-square-o"></i></span>
                    <h2>Verificar validações</h2>
                </header>
                <div>
                    <div class="widget-body no-padding">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th width="5">QTD</th>
                                    <th width="130">Linha Validada</th>
                                    <th>Descrição</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($rows as $kRow => $vRow)
                                    <tr>
                                        <td>#{{ $kRow + 1 }}</td>
                                        <td>
                                            @if (($vRow['linha'] ?? '') !== '')
                                                {{ ($vRow['linha'] ?? 0) + 1 }}
                                            @else
                                                Validação Geral
                                            @endif
                                        </td>
                                        <td>
                                            @if (is_array($vRow['descricao'] ?? null))
                                                @foreach ($vRow['descricao'] as $descricao)
                                                    @if ($descricao !== '') - {{ $descricao }} @endif
                                                @endforeach
                                            @else
                                                - {{ $vRow['descricao'] ?? '' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </article>
    </div>
</section>
@else
    <p class="text-muted">Nenhuma validação pendente na sessão. Erros de carga aparecem aqui após processamento completo (deferido).</p>
@endif

<p style="margin-top:20px;">
    <a href="{{ route('admin.importacao.add') }}" class="btn btn-labeled btn-success">
        <span class="btn-label"><i class="glyphicon glyphicon-ok"></i></span> Voltar para Re-importar
    </a>
</p>
@endsection
