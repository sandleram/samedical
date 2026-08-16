<?php

namespace App\Domain\Beneficiario;

/**
 * Cálculo de IMC puro (sem Laravel / Funcoes).
 */
final class ImcCalculator
{
    public static function calculate(?float $pesoKg, ?float $alturaMetros): ?float
    {
        if ($pesoKg === null || $alturaMetros === null || $pesoKg <= 0 || $alturaMetros <= 0) {
            return null;
        }

        return round($pesoKg / ($alturaMetros * $alturaMetros), 2);
    }
}
