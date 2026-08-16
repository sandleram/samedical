<?php

namespace App\Domain\Beneficio;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Beneficio
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $descricao,
        public readonly ?int $breakeven,
        public readonly ?string $contrato,
        public readonly ?int $clienteId,
        public readonly ?int $operadoraId,
        public readonly ?int $tipoBeneficioId,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataAtualizacao,
        public readonly ?\DateTimeImmutable $dataCancelamento,
        public readonly int $status,
        public readonly ?string $operadoraNome = null,
        public readonly ?string $tipoBeneficioDescricao = null,
        public readonly ?string $clienteNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'descricao' => $this->descricao,
            'breakeven' => $this->breakeven,
            'contrato' => $this->contrato,
            'cliente_id' => $this->clienteId,
            'operadora_id' => $this->operadoraId,
            'tipo_beneficio_id' => $this->tipoBeneficioId,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'data_cancelamento' => $this->dataCancelamento,
            'status' => $this->status,
            'operadora' => (object) ['nome' => $this->operadoraNome, 'id' => $this->operadoraId],
            'tipoBeneficio' => (object) ['descricao' => $this->tipoBeneficioDescricao, 'id' => $this->tipoBeneficioId],
            'cliente' => (object) ['nome' => $this->clienteNome, 'id' => $this->clienteId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
