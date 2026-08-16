@php
    $sit = (string) ($situacao ?? '');
    $style = $sit === 'Ativo'
        ? 'border-radius:6px; color:white;background-color:green; padding:2px 4px;'
        : 'border-radius:6px; color:white;background-color:red; padding:2px 4px;';
@endphp
@if ($sit !== '')
    <span style="{{ $style }}">{{ $sit }}</span>
@else
    -
@endif
