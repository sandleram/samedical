<?php

namespace App\Support;

use App\Models\Modulo;
use App\Models\PerfilModulo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Funcoes
{
    public static function passwordMatches(string $plain, string $stored): bool
    {
        if ($stored === '' || $plain === '') {
            return false;
        }

        if (strlen($stored) === 32 && ctype_xdigit($stored)) {
            return hash_equals(strtolower($stored), md5($plain));
        }

        return Hash::check($plain, $stored);
    }

    public static function shouldUpgradePassword(string $stored): bool
    {
        return strlen($stored) === 32 && ctype_xdigit($stored);
    }

    /**
     * @return array<string, int>
     */
    public static function permissionsFor(User $user): array
    {
        if ($user->isRoot()) {
            return Modulo::query()
                ->whereNotNull('controller')
                ->pluck('controller')
                ->filter()
                ->mapWithKeys(fn ($controller) => [strtolower((string) $controller) => 3])
                ->all();
        }

        return PerfilModulo::query()
            ->with('modulo')
            ->where('perfil_id', $user->perfil_id)
            ->get()
            ->filter(fn (PerfilModulo $pm) => $pm->modulo && $pm->modulo->controller)
            ->mapWithKeys(function (PerfilModulo $pm) {
                return [strtolower((string) $pm->modulo->controller) => (int) $pm->nivel];
            })
            ->all();
    }

    public static function can(string $controller, int $minLevel = 1): bool
    {
        $permissions = session('permissoes', []);
        $controller = strtolower($controller);
        $level = (int) ($permissions[$controller] ?? 0);

        return $level >= $minLevel;
    }
}
