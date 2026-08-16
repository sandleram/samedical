<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OndaCRoutesTest extends TestCase
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
    public static function ondaCIndexPaths(): array
    {
        return [
            'tipo_beneficio' => ['/admin/tipo_beneficio'],
            'procedimento' => ['/admin/procedimento'],
            'beneficio' => ['/admin/beneficio'],
            'subfatura' => ['/admin/subfatura'],
            'afastado' => ['/admin/afastado'],
            'absenteismo' => ['/admin/absenteismo'],
            'atendimento' => ['/admin/atendimento'],
            'beneficio_previdenciario' => ['/admin/beneficio_previdenciario'],
            'agendamento' => ['/admin/agendamento'],
        ];
    }

    #[DataProvider('ondaCIndexPaths')]
    public function test_onda_c_index_requires_authentication(string $path): void
    {
        $this->get($path)->assertRedirect(route('login'));
    }

    #[DataProvider('ondaCIndexPaths')]
    public function test_authenticated_root_can_open_onda_c_index(string $path): void
    {
        $this->seed();

        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $this->actingAs($user)
            ->withSession([
                'grupo_empresarial_id' => $user->grupo_empresarial_id,
                'cliente_id' => $user->cliente_id,
                'perfil_id' => $user->perfil_id,
                'permissoes' => [
                    'tipo_beneficio' => ['permissao' => 3],
                    'procedimento' => ['permissao' => 3],
                    'beneficio' => ['permissao' => 3],
                    'subfatura' => ['permissao' => 3],
                    'afastado' => ['permissao' => 3],
                    'absenteismo' => ['permissao' => 3],
                    'atendimento' => ['permissao' => 3],
                    'beneficio_previdenciario' => ['permissao' => 3],
                    'agendamento' => ['permissao' => 3],
                ],
            ])
            ->get($path)
            ->assertOk();
    }

    public function test_tipo_beneficio_and_procedimento_ok_without_cliente(): void
    {
        $this->seed();
        $user = User::query()->where('usuario', 'admin')->firstOrFail();

        $session = [
            'grupo_empresarial_id' => $user->grupo_empresarial_id,
            'cliente_id' => '',
            'perfil_id' => $user->perfil_id,
            'permissoes' => [
                'tipo_beneficio' => ['permissao' => 3],
                'procedimento' => ['permissao' => 3],
            ],
        ];

        $this->actingAs($user)->withSession($session)->get('/admin/tipo_beneficio')->assertOk();
        $this->actingAs($user)->withSession($session)->get('/admin/procedimento')->assertOk();
    }
}
