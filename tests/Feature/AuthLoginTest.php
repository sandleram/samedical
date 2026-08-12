<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware(PreventRequestForgery::class);
    }

    public function test_login_page_is_displayed(): void
    {
        $this->get('/')->assertOk();
    }

    public function test_users_can_authenticate_with_bcrypt_password(): void
    {
        $this->seed();

        $response = $this->from('/')
            ->post('/login', [
                'usuario' => 'admin',
                'senha' => 'admin123',
            ]);

        $response->assertRedirect(route('admin.home'));
        $this->assertAuthenticated();
    }

    public function test_legacy_md5_password_is_upgraded_on_login(): void
    {
        $this->seed();

        $response = $this->from('/')
            ->post('/login', [
                'usuario' => 'legado',
                'senha' => 'legado123',
            ]);

        $response->assertRedirect(route('admin.home'));
        $this->assertAuthenticated();

        $user = User::query()->where('usuario', 'legado')->firstOrFail();
        $this->assertTrue(Hash::check('legado123', $user->senha));
        $this->assertFalse(strlen($user->senha) === 32 && ctype_xdigit($user->senha));
    }
}
