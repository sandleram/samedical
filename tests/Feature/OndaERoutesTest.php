<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OndaERoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_onda_e_entry_points_require_authentication(): void
    {
        $this->get('/admin/importacao')->assertRedirect(route('login'));
        $this->get('/admin/importacao_nova')->assertRedirect(route('login'));
        $this->get('/admin/bi/lista')->assertRedirect(route('login'));
        $this->get('/admin/relatorio')->assertRedirect(route('login'));
    }

    public function test_authenticated_root_can_open_onda_e_entry_points(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $session = [
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'cliente_id' => 1,
            'perfil_id' => $user->perfil_id,
            'permissoes' => [
                'importacao' => ['permissao' => 3],
                'importacao_nova' => ['permissao' => 3],
                'bi' => ['permissao' => 3],
                'relatorio' => ['permissao' => 3],
            ],
        ];

        $this->actingAs($user)->withSession($session)
            ->get('/admin/importacao')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/importacao_nova')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/bi/lista')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/bi')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/relatorio')
            ->assertOk();

        $this->actingAs($user)->withSession($session)
            ->get('/admin/relatorio/afastados')
            ->assertOk();
    }

    public function test_importacao_requires_cliente_in_session(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)->withSession([
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'cliente_id' => '',
            'perfil_id' => $user->perfil_id,
            'permissoes' => ['importacao' => ['permissao' => 3]],
        ])->get('/admin/importacao')->assertRedirect(route('admin.home'));
    }
}
