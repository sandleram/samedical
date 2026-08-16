<?php

namespace App\Http\Middleware;

use App\Support\Funcoes;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * ACL por módulo + action (legado AppController).
 *
 * Uso:
 * - modulo:beneficiario        → minLevel = auto (inferido da action)
 * - modulo:beneficiario,auto   → idem
 * - modulo:beneficiario,2      → força nível mínimo 2
 */
class CheckModuloPermission
{
    public function handle(Request $request, Closure $next, string $controller, string|int $minLevel = 'auto'): Response
    {
        if ($request->user()?->isRoot()) {
            return $next($request);
        }

        $action = $request->route()?->getActionMethod() ?? 'index';
        $controller = Funcoes::permissionKey($controller);

        if (Funcoes::isAlwaysAllowed($controller, $action)) {
            return $next($request);
        }

        $required = ($minLevel === 'auto' || $minLevel === '')
            ? Funcoes::minLevelForAction($action)
            : (int) $minLevel;

        if (! Funcoes::can($controller, $required, $action)) {
            $level = Funcoes::permissionLevel($controller, $action);
            $frase = 'Seu Usuário Não tem Permissão de Acesso';

            if ($level === null) {
                $message = $frase.' !!!';
            } elseif ($level === 0) {
                $message = $frase.' para a área';
            } elseif ($required >= 3) {
                $message = $frase.' para Exclusões da área';
            } elseif ($required >= 2) {
                $message = $frase.' para Gerenciar da área';
            } else {
                $message = $frase.' !!!';
            }

            if ($request->expectsJson()) {
                abort(403, $message);
            }

            return redirect()
                ->route('admin.home')
                ->with('error', $message)
                ->withErrors(['permission' => $message]);
        }

        $effective = Funcoes::permissionLevel($controller, $action);
        if ($effective !== null) {
            view()->share('permissao', $effective);
        }

        return $next($request);
    }
}
