@php
    $id = (int) ($status ?? 0);
    $label = $id === 1 ? 'Ativo' : ($id === 2 ? 'Excluído' : 'Inativo');
    $style = match ($id) {
        0 => 'background-color:yellow; padding: 2px 4px; border-radius: 6px;',
        2 => 'background-color:red; color:white; padding: 2px 4px; border-radius: 6px;',
        default => 'background-color:green; color:white; padding: 2px 4px; border-radius: 6px;',
    };
@endphp
<span style="{{ $style }}">{{ $label }}</span>
