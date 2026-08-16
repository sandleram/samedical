@php
    $hasFilter = collect($search ?? [])->filter(fn ($v) => $v !== '' && $v !== null)->isNotEmpty();
@endphp
@if ($hasFilter)
    <label class="input">
        <a href="{{ $clearUrl }}" title="Limpar Filtros">
            <img src="{{ asset('img/sys/filter-clear.png') }}" alt="Limpar Filtros">
        </a>
        <a href="{{ $clearUrl }}">Limpar Filtros</a>
    </label>
@endif
