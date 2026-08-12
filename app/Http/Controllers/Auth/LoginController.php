<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Support\Funcoes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.home');
        }

        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->validated();

        /** @var User|null $user */
        $user = User::query()
            ->where('usuario', $credentials['usuario'])
            ->first();

        if (! $user || ! $user->isActive() || ! Funcoes::passwordMatches($credentials['senha'], (string) $user->senha)) {
            return back()
                ->withInput($request->only('usuario'))
                ->withErrors(['usuario' => 'Usuário ou senha inválidos.']);
        }

        if (Funcoes::shouldUpgradePassword((string) $user->senha)) {
            $user->senha = Hash::make($credentials['senha']);
            $user->save();
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        session([
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'perfil_id' => $user->perfil_id,
            'permissoes' => Funcoes::permissionsFor($user),
        ]);

        return redirect()->intended(route('admin.home'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
