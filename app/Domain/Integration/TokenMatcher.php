<?php

namespace App\Domain\Integration;

/**
 * Comparação segura de token de integração (REST/WS).
 * Sem dependência de framework.
 */
final class TokenMatcher
{
    public static function matches(string $expected, string $provided): bool
    {
        return $expected !== '' && hash_equals($expected, $provided);
    }
}
