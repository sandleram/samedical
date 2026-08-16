<?php

namespace App\Application\Modulo;

use App\Domain\Modulo\Modulo;
use App\Domain\Modulo\ModuloRepositoryInterface;

final class SaveModulo
{
    public function __construct(
        private readonly ModuloRepositoryInterface $modulos,
    ) {}

    public function execute(SaveModuloInput $input): Modulo
    {
        $attrs = $input->attributes;

        $payload = [
            'modulo_id' => (int) $attrs['modulo_id'],
            'nome' => $attrs['nome'],
            'controller' => $attrs['controller'],
            'order' => isset($attrs['order']) ? (int) $attrs['order'] : 0,
            'menu' => isset($attrs['menu']) ? (int) $attrs['menu'] : 0,
            'icon' => $attrs['icon'] ?? null,
            'status' => (int) $attrs['status'],
        ];

        if ($input->existingId !== null) {
            $payload['data_atualizacao'] = $input->now->format('Y-m-d H:i:s');

            return $this->modulos->update($input->existingId, $payload);
        }

        $payload['data_cadastro'] = $input->now->format('Y-m-d H:i:s');
        $payload['usuario_id'] = $input->userId;

        return $this->modulos->create($payload);
    }
}
