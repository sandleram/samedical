{{-- Espelho FuncoesHelper::menus('lista') --}}
@php
    $permissao = (int) ($permissao ?? 0);
@endphp
<div class="btn-group">
    <button class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
        Ações <span class="caret"></span>
    </button>
    <ul class="dropdown-menu dropdown-menu-right">
        @if ($permissao >= 1 && !empty($viewRoute))
            <li><a href="{{ $viewRoute }}">View</a></li>
        @endif
        @if ($permissao >= 2 && !empty($editRoute))
            <li><a href="{{ $editRoute }}">Edit</a></li>
        @endif
    </ul>
</div>
