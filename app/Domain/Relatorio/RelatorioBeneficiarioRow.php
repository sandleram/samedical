<?php

namespace App\Domain\Relatorio;

final class RelatorioBeneficiarioRow
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $cpf,
        public readonly ?string $nome,
        public readonly ?string $situacao,
        public readonly ?string $clienteNome,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cpf' => $this->cpf,
            'nome' => $this->nome,
            'situacao' => $this->situacao,
            'cliente' => (object) ['nome' => $this->clienteNome],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
