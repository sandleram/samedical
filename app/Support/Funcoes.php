<?php

namespace App\Support;

use App\Models\Modulo;
use App\Models\PerfilModulo;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

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
     * Monta Auth.permissoes no formato do legado (menu + ACL).
     *
     * @return array<string, array{permissao:int,id:int,modulo_id:int,nome:?string,controller:string,icon:?string,menu:int}>
     */
    public static function permissionsFor(User $user): array
    {
        $permissoes = [];

        if ($user->isRoot()) {
            $modulos = Modulo::query()
                ->whereIn('status', [1, 3])
                ->where('id', '>', 0)
                ->orderBy('order')
                ->get();

            foreach ($modulos as $modulo) {
                $key = self::permissionKey((string) $modulo->controller);
                if ($key === '') {
                    continue;
                }

                $permissoes[$key] = self::permissionEntry($modulo, 3);
            }

            return $permissoes;
        }

        $rows = PerfilModulo::query()
            ->with('modulo')
            ->where('perfil_id', $user->perfil_id)
            ->whereHas('modulo', function ($query) {
                $query->where('status', 1)->where('id', '>', 0);
            })
            ->get()
            ->sortBy(fn (PerfilModulo $pm) => (int) ($pm->modulo?->order ?? 0));

        foreach ($rows as $pm) {
            $modulo = $pm->modulo;
            if (! $modulo) {
                continue;
            }

            $key = self::permissionKey((string) $modulo->controller);
            if ($key === '') {
                continue;
            }

            $permissoes[$key] = self::permissionEntry($modulo, (int) $pm->permissao);
        }

        return $permissoes;
    }

    /**
     * Verifica permissão por controller e, opcionalmente, action fina (controller/action).
     * Preferência: chave fina → controller. Always-allowed e root são tratados no middleware;
     * always-allowed também libera aqui para uso em Blade/helpers.
     */
    public static function can(string $controller, int $minLevel = 1, ?string $action = null): bool
    {
        if (self::isAlwaysAllowed($controller, $action)) {
            return true;
        }

        $level = self::permissionLevel($controller, $action);

        if ($level === null) {
            return false;
        }

        return $level >= $minLevel;
    }

    /**
     * Gate por action: index/view≥1; add/edit≥2; delete≥3.
     */
    public static function canAction(string $controller, string $action): bool
    {
        return self::can($controller, self::minLevelForAction($action), $action);
    }

    /**
     * Nível efetivo na sessão (null = ausente).
     */
    public static function permissionLevel(string $controller, ?string $action = null): ?int
    {
        $permissions = session('permissoes', []);
        $controller = self::permissionKey($controller);

        if ($controller === '') {
            return null;
        }

        if ($action !== null && $action !== '') {
            $fineKey = $controller.'/'.self::permissionKey($action);
            if (array_key_exists($fineKey, $permissions)) {
                return self::levelFromEntry($permissions[$fineKey]);
            }
        }

        if (array_key_exists($controller, $permissions)) {
            return self::levelFromEntry($permissions[$controller]);
        }

        return null;
    }

    public static function minLevelForAction(string $action): int
    {
        $action = self::permissionKey($action);
        $action = preg_replace('/^admin_/', '', $action) ?? $action;
        $map = config('samed.action_min_levels', []);

        return (int) ($map[$action] ?? 1);
    }

    public static function isAlwaysAllowed(string $controller, ?string $action = null): bool
    {
        $controller = self::permissionKey($controller);
        $alwaysControllers = array_map(
            [self::class, 'permissionKey'],
            config('samed.always_allowed_controllers', [])
        );

        if (in_array($controller, $alwaysControllers, true)) {
            return true;
        }

        $alwaysActions = array_map(
            [self::class, 'permissionKey'],
            config('samed.always_allowed_actions', [])
        );

        if ($action !== null && $action !== '') {
            $fine = $controller.'/'.self::permissionKey($action);
            if (in_array($fine, $alwaysActions, true)) {
                return true;
            }
        }

        // Chave fina passada como único argumento (ex.: grupo_empresarial/selecione)
        if (str_contains($controller, '/')) {
            return in_array($controller, $alwaysActions, true);
        }

        return false;
    }

    /**
     * Resolve módulo a partir do nome da rota (config samed.route_module_map).
     */
    public static function moduleFromRouteName(?string $routeName): ?string
    {
        if ($routeName === null || $routeName === '') {
            return null;
        }

        $map = config('samed.route_module_map', []);

        return isset($map[$routeName]) ? self::permissionKey((string) $map[$routeName]) : null;
    }

    public static function permissionKey(string $controller): string
    {
        return strtolower(trim($controller));
    }

    /**
     * URL admin a partir do controller do módulo (compatível com rotas Laravel já portadas).
     */
    public static function adminModuleUrl(string $controller): string
    {
        $controller = strtolower(trim($controller));

        if ($controller === '' || $controller === 'home') {
            return route('admin.home');
        }

        $map = [
            'beneficiario' => 'admin.beneficiario.index',
            'usuario' => 'admin.usuario.index',
            'grupo_empresarial' => 'admin.grupo_empresarial.index',
            'cliente' => 'admin.cliente.index',
            'empresa' => 'admin.empresa.index',
            'operadora' => 'admin.operadora.index',
            'plano' => 'admin.plano.index',
            'modulo' => 'admin.modulo.index',
            'perfil' => 'admin.perfil.index',
            'parametro' => 'admin.parametro.index',
            'log' => 'admin.log.index',
            'tipo_beneficio' => 'admin.tipo_beneficio.index',
            'procedimento' => 'admin.procedimento.index',
            'beneficio' => 'admin.beneficio.index',
            'subfatura' => 'admin.subfatura.index',
            'afastado' => 'admin.afastado.index',
            'absenteismo' => 'admin.absenteismo.index',
            'atendimento' => 'admin.atendimento.index',
            'beneficio_previdenciario' => 'admin.beneficio_previdenciario.index',
            'agendamento' => 'admin.agendamento.index',
            'db' => 'admin.db.index',
            'mh_critico' => 'admin.mh_critico.index',
            'mh_negociacao' => 'admin.mh_negociacao.index',
            'mh_prestador' => 'admin.mh_prestador.index',
            'importacao' => 'admin.importacao.index',
            'importacao_nova' => 'admin.importacao_nova.index',
            'bi' => 'admin.bi.lista',
            'relatorio' => 'admin.relatorio.index',
        ];

        if (isset($map[$controller]) && Route::has($map[$controller])) {
            return route($map[$controller]);
        }

        return url('/admin/'.$controller);
    }

    public static function adminModuleAddUrl(string $controller): string
    {
        $controller = strtolower(trim($controller));

        $map = [
            'beneficiario' => 'admin.beneficiario.add',
            'mh_critico' => 'admin.mh_critico.add',
            'mh_negociacao' => 'admin.mh_negociacao.add',
            'mh_prestador' => 'admin.mh_prestador.add',
            'importacao' => 'admin.importacao.add',
            'importacao_nova' => 'admin.importacao_nova.add',
            'bi' => 'admin.bi.add',
        ];

        if (isset($map[$controller]) && Route::has($map[$controller])) {
            return route($map[$controller]);
        }

        return url('/admin/'.$controller.'/add');
    }

    public static function isModuleActive(string $controller): bool
    {
        $controller = strtolower(trim($controller));
        $path = trim(request()->path(), '/');

        if ($controller === '' || $controller === 'home') {
            return $path === 'admin/home' || $path === 'admin';
        }

        return str_starts_with($path, 'admin/'.$controller)
            || str_starts_with($path, 'admin/'.self::pluralizeController($controller));
    }

    public static function userAvatarUrl(?User $user): string
    {
        if ($user && filled($user->imagem)) {
            $relative = 'img/uploads/usuario/mini/'.$user->imagem;
            if (is_file(public_path($relative))) {
                return asset($relative);
            }
        }

        return match ($user?->sexo) {
            'Masculino' => asset('img/avatars/user_male.png'),
            'Feminino' => asset('img/avatars/user_female.png'),
            default => asset('img/avatars/male.png'),
        };
    }

    /**
     * IMC simples (peso kg / altura m²), altura em metros.
     */
    public static function formatCpf(?string $cpf): string
    {
        $digits = preg_replace('/\D+/', '', (string) $cpf) ?? '';
        if (strlen($digits) !== 11) {
            return (string) $cpf;
        }

        return substr($digits, 0, 3).'.'.substr($digits, 3, 3).'.'.substr($digits, 6, 3).'-'.substr($digits, 9, 2);
    }

    public static function formatCnpj(?string $cnpj): string
    {
        $digits = preg_replace('/\D+/', '', (string) $cnpj) ?? '';
        if (strlen($digits) !== 14) {
            return (string) $cnpj;
        }

        return substr($digits, 0, 2).'.'.substr($digits, 2, 3).'.'.substr($digits, 5, 3).'/'.substr($digits, 8, 4).'-'.substr($digits, 12, 2);
    }

    public static function idade(?\DateTimeInterface $nascimento): ?int
    {
        if (! $nascimento) {
            return null;
        }

        return (new \DateTimeImmutable)->diff($nascimento)->y;
    }

    public static function imc(?float $peso, ?float $alturaMetros): ?float
    {
        if ($peso === null || $alturaMetros === null || $peso <= 0 || $alturaMetros <= 0) {
            return null;
        }

        return round($peso / ($alturaMetros * $alturaMetros), 2);
    }

    private static function levelFromEntry(mixed $entry): int
    {
        if (is_array($entry)) {
            return (int) ($entry['permissao'] ?? 0);
        }

        return (int) ($entry ?? 0);
    }

    /**
     * @return array{permissao:int,id:int,modulo_id:int,nome:?string,controller:string,icon:?string,menu:int}
     */
    private static function permissionEntry(Modulo $modulo, int $permissao): array
    {
        return [
            'permissao' => $permissao,
            'id' => (int) $modulo->id,
            'modulo_id' => (int) ($modulo->modulo_id ?? 0),
            'nome' => $modulo->nome,
            'controller' => (string) $modulo->controller,
            'icon' => $modulo->icon,
            'menu' => (int) ($modulo->menu ?? 0),
        ];
    }

    private static function pluralizeController(string $controller): string
    {
        return match ($controller) {
            'beneficiario' => 'beneficiarios',
            default => $controller.'s',
        };
    }
}
