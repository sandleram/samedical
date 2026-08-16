<?php

namespace App\Domain\Plano;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Plano
{
    public function __construct(
        public readonly ?int $id,
        public readonly string $nome,
        public readonly ?string $codigoOperadora,
        public readonly ?int $operadoraId,
        public readonly ?int $tipoBeneficioId,
        public readonly ?int $clienteId,
        public readonly ?int $ordem,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly int $status,
        public readonly ?string $operadoraNome = null,
        public readonly ?string $tipoBeneficioDescricao = null,
    ) {}

    /**
     * Acesso estilo objeto para Blade (`$row->nome`, `$row->operadora->nome`).
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'nome' => $this->nome,
            'codigo_operadora' => $this->codigoOperadora,
            'operadora_id' => $this->operadoraId,
            'tipo_beneficio_id' => $this->tipoBeneficioId,
            'cliente_id' => $this->clienteId,
            'ordem' => $this->ordem,
            'data_cadastro' => $this->dataCadastro,
            'status' => $this->status,
            'operadora' => (object) ['nome' => $this->operadoraNome],
            'tipoBeneficio' => (object) ['descricao' => $this->tipoBeneficioDescricao],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
