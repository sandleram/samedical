@extends('layouts.admin')

@section('title', 'Dashboards BI')

@section('breadcrumb')
    <li>BI</li>
    <li>Todos</li>
@endsection

@section('content')
<p>&nbsp;</p>
<h4 style="border-bottom: 1px dotted #d3d3d3; padding-bottom: 5px;" class="titulo_pagina">
    Dashboards BI - Business Inteligence
</h4>
<a class="voltar_bi"> Voltar para lista </a>

<style>
    .kpi .well { border-radius: 8px; height:130px; box-shadow: 0px 4px 9px 0px #999999; }
    .titulo_kpi { font-size: 24px; }
    .subtitulo_kpi { font-size: 12px; }
    .voltar_bi { font-size: 12px; cursor:pointer; display:none; float:right; margin-top:-25px; margin-right:12px; }
</style>

<div class="row" style="margin-top:20px; text-align:center;">
    <div class="exibir_lista">
        @forelse ($list as $k => $v)
            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 kpi">
                <div class="well well-lg">
                    <p class="col-xs-12 titulo_kpi"
                       rel="{{ $k }}"
                       rel_titulo="{{ $v['titulo'] }}"
                       rel_subtitulo="{{ $v['subtitulo'] }}"
                       rel_link="{{ $v['link'] }}">
                        <a style="cursor:pointer;">{{ $v['titulo'] }}</a>
                    </p>
                    <p class="col-xs-12 subtitulo_kpi">{{ $v['subtitulo'] }}</p>
                </div>
            </div>
        @empty
            <p class="text-muted">Nenhum dashboard BI vinculado ao usuário.</p>
            <p>
                <a href="{{ route('admin.bi.gerencial') }}">Gerencial</a> |
                <a href="{{ route('admin.bi.medico') }}">Médico</a> |
                <a href="{{ route('admin.bi.rh') }}">RH</a>
            </p>
        @endforelse
    </div>

    @foreach ($list as $k => $v)
        <div class="src_iframe oculta_bi_all exibir_bi_{{ $k }}" style="display:none; float:left; width:100%;"></div>
    @endforeach
</div>

@push('scripts')
<script>
$(document).ready(function () {
    $('.titulo_kpi').click(function () {
        var rel = $(this).attr('rel');
        var rel_titulo = $(this).attr('rel_titulo');
        var rel_subtitulo = $(this).attr('rel_subtitulo');
        var parentHeight = $(window).height() - 140;
        var iframe2 = '<iframe src="' + $(this).attr('rel_link') + '" width="100%" height="' + parentHeight + '" style="border:none;"></iframe>';
        $('.oculta_bi_all').hide();
        $('.exibir_lista').hide();
        $('.exibir_bi_' + rel).show();
        $('.titulo_pagina').hide();
        $('.breadcrumb li:last').html(rel_subtitulo ? rel_titulo + ' <span style="font-size:12px;">(' + rel_subtitulo + ')</span>' : rel_titulo);
        $('.voltar_bi').show();
        setTimeout(function () { $('.exibir_bi_' + rel).html(iframe2); }, 300);
    });
    $('.voltar_bi').click(function () {
        $('.exibir_lista').fadeIn();
        $('.oculta_bi_all').hide().html('');
        $('.voltar_bi').hide();
        $('.titulo_pagina').show();
        $('.breadcrumb li:last').html('Todos');
    });
});
</script>
@endpush
@endsection
