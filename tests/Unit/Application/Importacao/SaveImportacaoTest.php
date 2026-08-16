<?php

namespace Tests\Unit\Application\Importacao;

use App\Application\Importacao\SaveImportacao;
use App\Domain\Importacao\Importacao;
use App\Domain\Importacao\ImportacaoRepositoryInterface;
use App\Domain\Importacao\ImportacaoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SaveImportacaoTest extends TestCase
{
    public function test_save_stores_file_and_creates_record(): void
    {
        $repo = new class implements ImportacaoRepositoryInterface
        {
            public string $stored = '';

            /** @var array<string, mixed> */
            public array $created = [];

            public function search(ImportacaoSearchCriteria $criteria, TenantScope $tenant): PagedResult
            {
                return new PagedResult([], 0, 15, 1);
            }

            public function findById(int $id, TenantScope $tenant): ?Importacao
            {
                return null;
            }

            public function create(array $data): Importacao
            {
                $this->created = $data;

                return new Importacao(
                    id: 42,
                    clienteId: (int) $data['cliente_id'],
                    tipoImportacao: (string) $data['tipo_importacao'],
                    arquivoImportado: (string) $data['arquivo_importado'],
                    avisos: $data['avisos'] ?? null,
                    dataCadastro: new DateTimeImmutable((string) $data['data_cadastro']),
                    usuarioCriadorId: $data['usuario_criador_id'] ?? null,
                    status: (int) $data['status'],
                );
            }

            public function storeUploadedFile(string $temporaryPath, string $originalFilename, string $extension, string $subdir): string
            {
                $this->stored = $subdir.'/'.$originalFilename;

                return 'stored_file.'.$extension;
            }

            public function tipoImportacaoOptions(): array
            {
                return [];
            }
        };

        $uc = new SaveImportacao($repo);
        $saved = $uc->execute(
            ['tipo_importacao' => 'beneficiario'],
            '/tmp/x',
            'planilha.xlsx',
            'xlsx',
            7,
            1,
            new DateTimeImmutable('2026-08-15 10:00:00'),
        );

        $this->assertSame(42, $saved->id);
        $this->assertSame('stored_file.xlsx', $repo->created['arquivo_importado']);
        $this->assertSame(7, $repo->created['cliente_id']);
        $this->assertStringContainsString('deferido', (string) $repo->created['avisos']);
        $this->assertSame('importacao/planilha.xlsx', $repo->stored);
    }
}
