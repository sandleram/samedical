<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeneficiarioRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_beneficiario_index_requires_authentication(): void
    {
        $this->get('/admin/beneficiario')->assertRedirect(route('login'));
    }

    public function test_authenticated_root_can_open_beneficiario_index(): void
    {
        $this->seed();

        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
                'perfil_id' => $user->perfil_id,
            ])
            ->get('/admin/beneficiario')
            ->assertOk();
    }

    public function test_plural_beneficiarios_redirects_to_singular(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->get('/admin/beneficiarios')
            ->assertRedirect(route('admin.beneficiario.index'));
    }

    public function test_home_dashboard_renders_for_authenticated_user(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
            ])
            ->get('/admin/home')
            ->assertOk()
            ->assertSee('Sistemas de Gerenciamento Médico');
    }
}
