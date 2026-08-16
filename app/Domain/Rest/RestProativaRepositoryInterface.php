<?php

namespace App\Domain\Rest;

interface RestProativaRepositoryInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function beneficiariosByCliente(int $clienteId): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function faturamentosByCliente(int $clienteId): array;

    /**
     * @return list<array<string, mixed>>
     */
    public function sinistrosByCliente(int $clienteId): array;

    /**
     * Dump por recurso (beneficio, cliente, grupo_estatistico, cronicos, subfaturas, procedimento).
     *
     * @return list<array<string, mixed>>
     */
    public function dump(string $resource): array;
}
