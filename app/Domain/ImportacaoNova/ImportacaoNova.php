<?php

namespace App\Domain\ImportacaoNova;

/**
 * Entidade de domínio. Sem dependências Laravel.
 */
final class ImportacaoNova
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?int $clienteId,
        public readonly ?string $nomeArquivo,
        public readonly ?string $tipoImportacao,
        public readonly ?string $arquivoImportado,
        public readonly ?string $avisos,
        public readonly ?string $linhasTotais,
        public readonly ?string $linhasProcessadas,
        public readonly ?\DateTimeImmutable $dataCadastro,
        public readonly ?\DateTimeImmutable $dataAtualizacao,
        public readonly ?int $usuarioCriadorId,
        public readonly ?int $usuarioAtualizacaoId,
        public readonly int $statusProcesso,
        public readonly int $status,
        public readonly ?string $clienteNome = null,
    ) {}

    public function __get(string $name): mixed
    {
        return match ($name) {
            'id' => $this->id,
            'cliente_id' => $this->clienteId,
            'nome_arquivo' => $this->nomeArquivo,
            'tipo_importacao' => $this->tipoImportacao,
            'arquivo_importado' => $this->arquivoImportado,
            'avisos' => $this->avisos,
            'linhas_totais' => $this->linhasTotais,
            'linhas_processadas' => $this->linhasProcessadas,
            'data_cadastro' => $this->dataCadastro,
            'data_atualizacao' => $this->dataAtualizacao,
            'usuario_criador_id' => $this->usuarioCriadorId,
            'usuario_atualizacao_id' => $this->usuarioAtualizacaoId,
            'status_processo' => $this->statusProcesso,
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
