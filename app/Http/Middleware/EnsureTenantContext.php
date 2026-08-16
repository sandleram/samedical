<?php

namespace App\Http\Middleware;

use App\Support\Funcoes;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            if (! session()->has('grupo_empresarial_id') && $user->grupo_empresarial_id) {
                session(['grupo_empresarial_id' => $user->grupo_empresarial_id]);
            }

            if (! session()->has('perfil_id') && $user->perfil_id) {
                session(['perfil_id' => $user->perfil_id]);
            }

            // Reconstrói menu/ACL a cada request (como AppController do legado).
            session([
                'permissoes' => Funcoes::permissionsFor($user),
            ]);
        }

        return $next($request);
    }
}
