<?php

namespace Tests\Unit;

use App\Support\Funcoes;
use Tests\TestCase;

class FuncoesAclTest extends TestCase
{
    public function test_min_level_for_action_matches_samed_map(): void
    {
        $this->assertSame(1, Funcoes::minLevelForAction('index'));
        $this->assertSame(1, Funcoes::minLevelForAction('show'));
        $this->assertSame(1, Funcoes::minLevelForAction('view'));
        $this->assertSame(1, Funcoes::minLevelForAction('admin_index'));

        $this->assertSame(2, Funcoes::minLevelForAction('create'));
        $this->assertSame(2, Funcoes::minLevelForAction('store'));
        $this->assertSame(2, Funcoes::minLevelForAction('add'));
        $this->assertSame(2, Funcoes::minLevelForAction('edit'));
        $this->assertSame(2, Funcoes::minLevelForAction('update'));
        $this->assertSame(2, Funcoes::minLevelForAction('admin_add'));

        $this->assertSame(3, Funcoes::minLevelForAction('destroy'));
        $this->assertSame(3, Funcoes::minLevelForAction('delete'));
        $this->assertSame(3, Funcoes::minLevelForAction('admin_delete'));
    }

    public function test_always_allowed_controllers_and_actions(): void
    {
        $this->assertTrue(Funcoes::isAlwaysAllowed('home'));
        $this->assertTrue(Funcoes::isAlwaysAllowed('Home'));
        $this->assertTrue(Funcoes::isAlwaysAllowed('kcfinder'));
        $this->assertTrue(Funcoes::isAlwaysAllowed('grupo_empresarial', 'selecione'));
        $this->assertTrue(Funcoes::isAlwaysAllowed('Grupo_empresarial/selecione'));
        $this->assertTrue(Funcoes::isAlwaysAllowed('usuario', 'atualiza_session_cliente'));
        $this->assertTrue(Funcoes::isAlwaysAllowed('blob', 'download'));

        $this->assertFalse(Funcoes::isAlwaysAllowed('beneficiario'));
        $this->assertFalse(Funcoes::isAlwaysAllowed('beneficiario', 'index'));
    }

    public function test_can_respects_session_permission_levels_and_fine_keys(): void
    {
        session([
            'permissoes' => [
                'beneficiario' => [
                    'permissao' => 1,
                    'id' => 10,
                    'modulo_id' => 0,
                    'nome' => 'Beneficiário',
                    'controller' => 'beneficiario',
                    'icon' => 'fa-user',
                    'menu' => 0,
                ],
                'beneficiario/all' => [
                    'permissao' => 2,
                    'id' => 11,
                    'modulo_id' => 10,
                    'nome' => 'Beneficiário All',
                    'controller' => 'beneficiario/all',
                    'icon' => null,
                    'menu' => 0,
                ],
            ],
        ]);

        $this->assertTrue(Funcoes::can('beneficiario', 1));
        $this->assertFalse(Funcoes::can('beneficiario', 2));
        $this->assertFalse(Funcoes::canAction('beneficiario', 'create'));
        $this->assertTrue(Funcoes::canAction('beneficiario', 'index'));

        $this->assertTrue(Funcoes::can('beneficiario', 2, 'all'));
        $this->assertTrue(Funcoes::canAction('beneficiario', 'all'));
        $this->assertFalse(Funcoes::can('beneficiario', 3, 'all'));

        $this->assertFalse(Funcoes::can('afastado', 1));
        $this->assertTrue(Funcoes::can('home', 3));
    }

    public function test_module_from_route_name(): void
    {
        $this->assertSame('home', Funcoes::moduleFromRouteName('admin.home'));
        $this->assertSame('beneficiario', Funcoes::moduleFromRouteName('admin.beneficiario.index'));
        $this->assertSame('beneficiario', Funcoes::moduleFromRouteName('admin.beneficiarios.index'));
        $this->assertNull(Funcoes::moduleFromRouteName('login'));
    }
}
