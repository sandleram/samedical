<?php

namespace App\Domain\Relatorio;

/**
 * Linha de relatório de afastados (somente leitura).
 */
final class RelatorioAfastadoRow
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $cid,
        public readonly ?string $situacao,
        public readonly ?\DateTimeImmutable $dataInicioAfastamento,
        public readonly ?\DateTimeImmutable $dataFimAfastamento,
        public readonly ?string $beneficiarioNome,
        public readonly ?string $beneficiarioCpf,
        public readonly ?string $clienteNome,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cid' => $this->cid,
            'situacao' => $this->situacao,
            'data_inicio_afastamento' => $this->dataInicioAfastamento,
            'data_fim_afastamento' => $this->dataFimAfastamento,
            'beneficiario' => (object) [
                'nome' => $this->beneficiarioNome,
                'cpf' => $this->beneficiarioCpf,
                'cliente' => (object) ['nome' => $this->clienteNome],
            ],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
