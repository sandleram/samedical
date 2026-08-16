<?php

namespace App\Domain\Atendimento;

/**
 * Labels de tipo de atendimento (legado). Sem Laravel.
 */
final class TipoAtendimento
{
    /**
     * @return array<int|string, string>
     */
    public static function labels(bool $withEmpty = false): array
    {
        $arr = [
            1 => 'Acolhimento',
            2 => 'Acompanhamento',
            3 => 'Orientação',
            4 => 'Outros',
        ];

        return $withEmpty ? (['' => 'Selecione...'] + $arr) : $arr;
    }
}
