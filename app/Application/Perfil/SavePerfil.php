<?php

namespace App\Application\Perfil;

use App\Domain\Perfil\Perfil;
use App\Domain\Perfil\PerfilRepositoryInterface;

final class SavePerfil
{
    public function __construct(
        private readonly PerfilRepositoryInterface $perfis,
    ) {}

    public function execute(SavePerfilInput $input): Perfil
    {
        $attrs = $input->attributes;

        $payload = [
            'nome' => $attrs['nome'],
            'tipo' => (int) $attrs['tipo'],
            'status' => (int) $attrs['status'],
        ];

        if ($input->existingId !== null) {
            $payload['data_atualizacao'] = $input->now->format('Y-m-d H:i:s');
        } else {
            $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');
            $payload['usuario_criador'] = $input->userId;
        }

        /** @var array<int, array{id?: int|string|null, permissao?: int|string|null}> $perfilModulos */
        $perfilModulos = (array) ($attrs['PerfilModulo'] ?? []);

        return $this->perfis->save($payload, $perfilModulos, $input->existingId);
    }
}
