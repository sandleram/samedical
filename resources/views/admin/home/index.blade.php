@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<p>&nbsp;</p>
<h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;">
    @if ($logoGE !== '')
        <img src="{{ $logoGE }}" alt="" title="" style="width:101px !important; margin-top: -9px; padding-left: 0; float:right;">
    @endif
    Sistemas de Gerenciamento Médico
</h4>

<div class="row" style="margin-top:20px;">
    <style>
        .kpi .well {
            border-radius: 8px;
            height: 130px;
            -webkit-box-shadow: 0px 4px 9px 0px #999999;
            box-shadow: 0px 4px 9px 0px #999999;
        }
        .kpi2 .well {
            border-radius: 8px;
            height: 150px;
            -webkit-box-shadow: 0px 4px 9px 0px #999999;
            box-shadow: 0px 4px 9px 0px #999999;
        }
        .titulo_kpi { font-size: 14px; }
        .result_kpi { font-size: 40px; margin-top: 4px; }
        .titulo_gerencial {
            border-bottom: 1px solid #999;
            font-weight: bold;
            padding-bottom: 6px;
        }
    </style>

    @php
        $kpiClass = 'col-xs-12 col-sm-6 col-md-4 col-lg-4 kpi ';
        $kpiTitClass = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 titulo_kpi';
        $kpiResClass = 'col-xs-12 col-sm-12 col-md-12 col-lg-12 result_kpi';
        $kpis = $row['charts']['kpi'] ?? [];
    @endphp

    <div class="rows" style="margin-top:20px; text-align:center;">
        <div class="rows">
            @foreach ($kpis as $kkpi => $kpi)
                <div class="{{ $kpiClass }}">
                    <div class="well well-lg">
                        <p class="{{ $kpiTitClass }}">{{ $kpi['titulo'] }}</p>
                        <p class="{{ $kpiResClass }} @if($kkpi === 'total_beneficiarios') result_kpi_animate @endif"
                           rel_result="{{ $kpi['valor'] }}">
                            @if (!empty($kpi['url']))
                                <a href="{{ $kpi['url'] }}">{{ $kpi['valor'] }}</a>
                            @else
                                {{ $kpi['valor'] }}
                            @endif
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="row" style="margin-top:20px;">
    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="jarviswidget" id="wid-id-2" data-widget-editbutton="false">
            <header>
                <span class="widget-icon"><i class="fa fa-bar-chart-o"></i></span>
                <h2>Importações</h2>
            </header>
            <div>
                <div class="widget-body no-padding">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Importação</th>
                                <th style="text-align:center;">Qtd. Mensais</th>
                                <th style="text-align:center;">Qtd. Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach (['afastado' => 'Afastados', 'beneficio_previdenciario' => 'Benefício Previdenciário', 'absenteismo' => 'Absenteísmo'] as $tipo => $label)
                                @foreach (($notificacoes[$tipo] ?? []) as $cliente_id => $resposta)
                                    @if (isset($selectCliente[$cliente_id]))
                                        @php
                                            $sparkline = implode(',', $resposta);
                                            $soma = array_sum($resposta);
                                        @endphp
                                        <tr>
                                            <td>{{ $selectCliente[$cliente_id] }}</td>
                                            <td>{{ $label }}</td>
                                            <td style="text-align:center;">
                                                <span class="sparkline txt-color-blue"
                                                      data-sparkline-type="bar"
                                                      data-sparkline-width="50px"
                                                      data-sparkline-barwidth="3"
                                                      data-sparkline-height="15px">{{ $sparkline }}</span>
                                            </td>
                                            <td style="text-align:center;">{{ $soma }}</td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </article>
</div>

@if ($exibeNotificacao)
    <div id="dialog-message" title="Warning System">
        <p>Notificamos através desta mensagem, que será realizado uma manutenção no servidor.</p>
        <p>A manutenção está programada para ocorrer no dia 17/07/2026, iniciando às 22h00min e com previsão de término às 02h00min do dia 18/07/2026.</p>
        <p>Durante este processo, o sistema ficará indisponível por cerca de 05 minutos e por algumas vezes, poderá apresentar instabilidade.</p>
        <div class="hr hr-12 hr-double"></div>
    </div>
@endif
@endsection

@push('scripts')
<script>
$(document).ready(function () {
    if ($('#dialog-message').length && $.fn.dialog) {
        $.widget("ui.dialog", $.extend({}, $.ui.dialog.prototype, {
            _title: function (title) {
                if (!this.options.title) {
                    title.html("&#160;");
                } else {
                    title.html(this.options.title);
                }
            }
        }));

        $("#dialog-message").dialog({
            autoOpen: true,
            modal: true,
            width: 500,
            title: "<div class='widget-header'><h4 style='color: #ff2600;'><i class='icon-ok'></i>Aviso do Sistema</h4></div>",
            buttons: [{
                html: "<i class='fa fa-check'></i>&nbsp; OK",
                "class": "btn btn-primary",
                click: function () {
                    $(this).dialog("close");
                }
            }]
        });
    }

    if ($.fn.sparkline) {
        $('.sparkline').sparkline('html', { type: 'bar', height: '15px', barWidth: 3 });
    }
});
</script>
@endpush
