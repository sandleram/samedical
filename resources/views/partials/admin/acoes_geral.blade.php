{{-- Espelho FuncoesHelper::menus('geral') — Cake AppController --}}
@php
    $permissao = (int) ($permissao ?? 0);
    $addRoute = $addRoute ?? null;
    $viewRoute = $viewRoute ?? null;
    $indexRoute = $indexRoute ?? null;
    $novoLabel = $novoLabel ?? 'Novo';
    $context = $context ?? 'index';
@endphp
<div class="btn-group" style="float:right; margin-bottom: 10px;">
    <button class="btn btn-primary btn-sm dropdown-toggle" data-toggle="dropdown">
        Ações <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        @if ($context === 'index' && $addRoute && $permissao >= 2)
            <li><a href="{{ $addRoute }}">{{ $novoLabel }}</a></li>
        @endif
        @if ($context === 'add' && $addRoute && $permissao >= 2)
            <li><a href="{{ $addRoute }}">{{ $novoLabel }}</a></li>
        @endif
        @if ($viewRoute)
            <li><a href="{{ $viewRoute }}">Visualizar</a></li>
        @endif
        @if ($indexRoute)
            <li><a href="{{ $indexRoute }}">Lista</a></li>
        @endif
    </ul>
</div>
<div class="clearfix"></div>
