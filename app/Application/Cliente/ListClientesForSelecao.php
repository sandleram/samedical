<?php

namespace App\Application\Cliente;

use App\Domain\Cliente\ClienteRepositoryInterface;

final class ListClientesForSelecao
{
    public function __construct(
        private readonly ClienteRepositoryInterface $repository,
    ) {}

    /**
     * @return array{
     *     selectClienteNew: array<int, list<array{ge_nome: string, cliente_id: int, cliente_status: int, cliente_nome: string}>>,
     *     selectClienteGENew: array<int, int>
     * }
     */
    public function execute(int $usuarioId, int $perfilId, bool $isRoot): array
    {
        $clientes = $this->repository->listForSelecao($usuarioId, $perfilId, $isRoot);

        $selectClienteNew = [];
        $selectClienteGENew = [];

        foreach ($clientes as $cliente) {
            $geId = (int) ($cliente->grupoEmpresarialId ?? 0);
            $selectClienteGENew[(int) $cliente->id] = $geId;
            $selectClienteNew[$geId][] = [
                'ge_nome' => $cliente->grupoEmpresarialNome ?? ('GE #'.$geId),
                'cliente_id' => (int) $cliente->id,
                'cliente_status' => (int) $cliente->status,
                'cliente_nome' => $cliente->nome,
            ];
        }

        return [
            'selectClienteNew' => $selectClienteNew,
            'selectClienteGENew' => $selectClienteGENew,
        ];
    }
}
