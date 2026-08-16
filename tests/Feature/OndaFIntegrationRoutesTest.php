<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OndaFIntegrationRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_rest_index_returns_failed_payload(): void
    {
        $this->getJson('/api/rest')
            ->assertOk()
            ->assertJsonPath('response.status', 'failed');
    }

    public function test_rest_beneficiario_fails_without_token(): void
    {
        config(['samed.rest.token' => 'test-token-secret']);

        $this->getJson('/api/rest/bi_proativa_beneficiario?cliente_id=1')
            ->assertOk()
            ->assertJsonPath('response.status', 'failed');
    }

    public function test_ws_call_bi_requires_token(): void
    {
        config(['samed.ws.token' => 'ws-secret']);

        $this->getJson('/api/ws/call_bi_beneficiarios')
            ->assertForbidden()
            ->assertJsonPath('status', 'failed');
    }

    public function test_blob_download_requires_authentication(): void
    {
        $this->get('/admin/blob/download/'.str_repeat('a', 32))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_root_can_open_db_index(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
                'perfil_id' => $user->perfil_id,
            ])
            ->get('/admin/db')
            ->assertOk()
            ->assertSee('Utilitário DB');
    }

    public function test_blob_download_is_always_allowed_for_authenticated_user(): void
    {
        $this->assertTrue(\App\Support\Funcoes::isAlwaysAllowed('blob', 'download'));

        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
            ])
            ->get('/admin/blob/download/'.str_repeat('0', 32))
            ->assertRedirect();
    }
}
