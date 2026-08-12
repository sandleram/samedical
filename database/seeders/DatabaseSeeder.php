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
        $grupo = GrupoEmpresarial::query()->firstOrCreate(
            ['id' => 1],
            ['nome' => 'Grupo Demo', 'status' => 1]
        );

        $perfilRoot = Perfil::query()->firstOrCreate(
            ['id' => 1],
            ['nome' => 'Root', 'descricao' => 'Acesso total', 'tipo' => 'root', 'status' => 1]
        );

        $cliente = Cliente::query()->firstOrCreate(
            ['id' => 1],
            [
                'nome' => 'Cliente Demo',
                'grupo_empresarial_id' => $grupo->id,
                'status' => 1,
            ]
        );

        $empresa = Empresa::query()->firstOrCreate(
            ['id' => 1],
            [
                'nome' => 'Empresa Demo LTDA',
                'cnpj' => '00000000000191',
                'cliente_id' => $cliente->id,
                'status' => 1,
            ]
        );

        $home = Modulo::query()->firstOrCreate(
            ['controller' => 'home'],
            ['nome' => 'Home', 'menu' => 'Home', 'icon' => 'fa-home', 'ordem' => 1, 'status' => 1]
        );

        $beneficiario = Modulo::query()->firstOrCreate(
            ['controller' => 'beneficiario'],
            ['nome' => 'Beneficiários', 'menu' => 'Beneficiários', 'icon' => 'fa-users', 'ordem' => 2, 'status' => 1]
        );

        foreach ([$home, $beneficiario] as $modulo) {
            PerfilModulo::query()->firstOrCreate(
                [
                    'perfil_id' => $perfilRoot->id,
                    'modulo_id' => $modulo->id,
                ],
                ['nivel' => 3]
            );
        }

        User::query()->updateOrCreate(
            ['usuario' => 'admin'],
            [
                'id' => 1,
                'nome' => 'Administrador',
                'senha' => Hash::make('admin123'),
                'email' => 'admin@samed.local',
                'perfil_id' => $perfilRoot->id,
                'grupo_empresarial_id' => $grupo->id,
                'status' => 1,
            ]
        );

        // Usuário legado com senha MD5 para validar upgrade no login
        User::query()->updateOrCreate(
            ['usuario' => 'legado'],
            [
                'nome' => 'Usuário Legado',
                'senha' => md5('legado123'),
                'email' => 'legado@samed.local',
                'perfil_id' => $perfilRoot->id,
                'grupo_empresarial_id' => $grupo->id,
                'status' => 1,
            ]
        );

        Beneficiario::query()->firstOrCreate(
            ['cpf' => '00000000000'],
            [
                'nome' => 'Beneficiário Demo',
                'matricula' => 'A001',
                'data_nascimento' => '1990-01-15',
                'cliente_id' => $cliente->id,
                'empresa_id' => $empresa->id,
                'grupo_empresarial_id' => $grupo->id,
                'status' => 1,
            ]
        );
    }
}
