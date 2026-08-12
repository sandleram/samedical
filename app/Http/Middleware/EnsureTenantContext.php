<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureTenantContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && ! session()->has('grupo_empresarial_id') && $user->grupo_empresarial_id) {
            session(['grupo_empresarial_id' => $user->grupo_empresarial_id]);
        }

        return $next($request);
    }
}
