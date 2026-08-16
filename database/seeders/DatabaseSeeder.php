<?php

namespace Database\Seeders;

use App\Models\Beneficiario;
use App\Models\Cliente;
use App\Models\Empresa;
use App\Models\GrupoEmpresarial;
use App\Models\Modulo;
use App\Models\Perfil;
use App\Models\PerfilModulo;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $grupo = GrupoEmpresarial::query()->updateOrCreate(
            ['id' => 1],
            [
                'nome' => 'Grupo Demo',
                'data_cadastro' => $now,
                'status' => 1,
            ]
        );

        $perfilRoot = Perfil::query()->updateOrCreate(
            ['id' => 1],
            [
                'nome' => 'Root',
                'descricao' => 'Acesso total',
                'tipo' => 1,
                'usuario_criador' => 1,
                'status' => 1,
            ]
        );

        $cliente = Cliente::query()->updateOrCreate(
            ['id' => 1],
            [
                'nome' => 'Cliente Demo',
                'grupo_empresarial_id' => $grupo->id,
                'data_cadastro' => $now,
                'status' => 1,
            ]
        );

        $empresa = Empresa::query()->updateOrCreate(
            ['id' => 1],
            [
                'nome' => 'Empresa Demo LTDA',
                'cnpj' => '00000000000191',
                'cliente_id' => $cliente->id,
                'data_cadastro' => $now,
                'status' => 1,
            ]
        );

        $home = Modulo::query()->updateOrCreate(
            ['controller' => 'home'],
            [
                'nome' => 'Home',
                'menu' => 2,
                'icon' => 'fa-home',
                'order' => 1,
                'usuario_id' => 1,
                'status' => 1,
            ]
        );

        $beneficiarioModulo = Modulo::query()->updateOrCreate(
            ['controller' => 'beneficiario'],
            [
                'nome' => 'Beneficiários',
                'menu' => 2,
                'icon' => 'fa-users',
                'order' => 2,
                'usuario_id' => 1,
                'status' => 1,
            ]
        );

        foreach ([$home, $beneficiarioModulo] as $modulo) {
            PerfilModulo::query()->updateOrCreate(
                [
                    'perfil_id' => $perfilRoot->id,
                    'modulo_id' => $modulo->id,
                ],
                [
                    'permissao' => 3,
                    'status' => 1,
                ]
            );
        }

        User::query()->updateOrCreate(
            ['usuario' => 'admin'],
            [
                'id' => 1,
                'nome' => 'Administrador',
                'apelido' => 'Admin',
                'senha' => Hash::make('admin123'),
                'email' => 'admin@samed.local',
                'perfil_id' => $perfilRoot->id,
                'grupo_empresarial_id' => $grupo->id,
                'status' => 1,
            ]
        );

        User::query()->updateOrCreate(
            ['usuario' => 'legado'],
            [
                'nome' => 'Usuário Legado',
                'apelido' => 'Legado',
                'senha' => md5('legado123'),
                'email' => 'legado@samed.local',
                'perfil_id' => $perfilRoot->id,
                'grupo_empresarial_id' => $grupo->id,
                'status' => 1,
            ]
        );

        Beneficiario::query()->updateOrCreate(
            ['cpf' => '00000000000'],
            [
                'nome' => 'Beneficiário Demo',
                'cod_matricula' => 'A001',
                'data_nascimento' => '1990-01-15',
                'cliente_id' => $cliente->id,
                'empresa_id' => $empresa->id,
                'usuario_criador_id' => 1,
                'processo' => '1',
                'vl_ambulatorio' => 1.00,
                'status' => 1,
            ]
        );
    }
}
