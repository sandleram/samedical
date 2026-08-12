<?php

namespace App\Http\Middleware;

use App\Support\Funcoes;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModuloPermission
{
    public function handle(Request $request, Closure $next, string $controller, int $minLevel = 1): Response
    {
        if ($request->user()?->isRoot()) {
            return $next($request);
        }

        if (! Funcoes::can($controller, $minLevel)) {
            abort(403, 'Sem permissão para acessar este módulo.');
        }

        return $next($request);
    }
}
