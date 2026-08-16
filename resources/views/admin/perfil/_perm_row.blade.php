@php
    $mid = (int) $mod->id;
    $saved = $permissoesSalvas[$mid] ?? ['id' => '', 'permissao' => 0];
    $current = (int) ($saved['permissao'] ?? 0);
@endphp
<tr @if ($style) style="{{ $style }}" @endif>
    <td>{!! $prefix !!}{{ $mod->nome }}</td>
    @for ($level = 0; $level <= 3; $level++)
        <td align="center">
            @if ($level === 0)
                <input type="hidden" name="PerfilModulo[{{ $mid }}][id]" value="{{ $saved['id'] ?? '' }}">
                <input type="hidden" name="PerfilModulo[{{ $mid }}][modulo_id]" value="{{ $mid }}">
            @endif
            <input type="radio"
                   name="PerfilModulo[{{ $mid }}][permissao]"
                   value="{{ $level }}"
                   @checked($current === $level)>
        </td>
    @endfor
</tr>
