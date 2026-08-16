<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Onda D — MH crítico / histórico / negociação / prestador.
 */
class MhModulesRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_mh_modules_require_authentication(): void
    {
        $this->get('/admin/mh_critico')->assertRedirect(route('login'));
        $this->get('/admin/mh_negociacao')->assertRedirect(route('login'));
        $this->get('/admin/mh_prestador')->assertRedirect(route('login'));
        $this->get('/admin/mh_critico_historico/1')->assertRedirect(route('login'));
    }

    public function test_authenticated_root_can_open_mh_indexes(): void
    {
        $this->seed();

        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $session = [
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'cliente_id' => $user->cliente_id,
            'perfil_id' => $user->perfil_id,
        ];

        $this->actingAs($user)->withSession($session)
            ->get('/admin/mh_critico')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/mh_negociacao')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/mh_prestador')
            ->assertOk();
    }

    public function test_mh_add_forms_render_for_root(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $session = [
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'cliente_id' => $user->cliente_id,
            'perfil_id' => $user->perfil_id,
        ];

        $this->actingAs($user)->withSession($session)
            ->get('/admin/mh_critico/add')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/mh_negociacao/add')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/mh_prestador/add')
            ->assertOk();
    }
}
