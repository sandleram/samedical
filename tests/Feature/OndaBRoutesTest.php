<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OndaBRoutesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    /**
     * @return array<string, array{0: string}>
     */
    public static function ondaBIndexPaths(): array
    {
        return [
            'usuario' => ['/admin/usuario'],
            'grupo_empresarial' => ['/admin/grupo_empresarial'],
            'operadora' => ['/admin/operadora'],
            'plano' => ['/admin/plano'],
            'modulo' => ['/admin/modulo'],
            'perfil' => ['/admin/perfil'],
            'parametro' => ['/admin/parametro'],
            'log' => ['/admin/log'],
        ];
    }

    #[DataProvider('ondaBIndexPaths')]
    public function test_onda_b_index_requires_authentication(string $path): void
    {
        $this->get($path)->assertRedirect(route('login'));
    }

    #[DataProvider('ondaBIndexPaths')]
    public function test_authenticated_root_can_open_onda_b_index(string $path): void
    {
        $this->seed();

        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
                'perfil_id' => $user->perfil_id,
                'permissoes' => [
                    'usuario' => ['permissao' => 3],
                    'grupo_empresarial' => ['permissao' => 3],
                    'cliente' => ['permissao' => 3],
                    'empresa' => ['permissao' => 3],
                    'operadora' => ['permissao' => 3],
                    'plano' => ['permissao' => 3],
                    'modulo' => ['permissao' => 3],
                    'perfil' => ['permissao' => 3],
                    'parametro' => ['permissao' => 3],
                    'log' => ['permissao' => 3],
                ],
            ])
            ->get($path)
            ->assertOk();
    }

    public function test_cliente_and_empresa_index_ok_with_tenant(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $session = [
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'cliente_id' => $user->cliente_id,
            'perfil_id' => $user->perfil_id,
            'permissoes' => [
                'cliente' => ['permissao' => 3],
                'empresa' => ['permissao' => 3],
            ],
        ];

        $this->actingAs($user)->withSession($session)->get('/admin/cliente')->assertOk();
        $this->actingAs($user)->withSession($session)->get('/admin/empresa')->assertOk();
    }

    public function test_cliente_index_redirects_when_grupo_cleared(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        // Empty string keeps session key present so tenant middleware will not refill from user.
        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => '',
                'cliente_id' => '',
                'permissoes' => ['cliente' => ['permissao' => 3]],
            ])
            ->get('/admin/cliente')
            ->assertRedirect(route('admin.grupo_empresarial.selecione'));
    }

    public function test_empresa_index_redirects_when_tenant_cleared(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => '',
                'cliente_id' => '',
                'permissoes' => ['empresa' => ['permissao' => 3]],
            ])
            ->get('/admin/empresa')
            ->assertRedirect(route('admin.grupo_empresarial.selecione'));
    }

    public function test_grupo_empresarial_selecione_renders(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
                'perfil_id' => $user->perfil_id,
            ])
            ->get('/admin/grupo_empresarial/selecione')
            ->assertOk();
    }
}
