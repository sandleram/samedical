<?php

namespace App\Domain\Importacao;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class Importacao
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $clienteId,
        public readonly ?string $tipoImportacao,
        public readonly ?string $arquivoImportado,
        public readonly ?string $avisos,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?int $usuarioCriadorId,
        public readonly int $status,
        public readonly ?string $clienteNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cliente_id' => $this->clienteId,
            'tipo_importacao' => $this->tipoImportacao,
            'arquivo_importado' => $this->arquivoImportado,
            'avisos' => $this->avisos,
            'data_cadastro' => $this->dataCadastro,
            'usuario_criador_id' => $this->usuarioCriadorId,
            'status' => $this->status,
            'cliente' => (object) ['nome' => $this->clienteNome, 'id' => $this->clienteId],
            default => null,
        };
    }

    public function __isset(string $name): bool
    {
        return $this->__get($name) !== null;
    }
}
