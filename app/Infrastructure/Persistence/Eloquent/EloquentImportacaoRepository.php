<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Importacao\Importacao as ImportacaoEntity;
use App\Domain\Importacao\ImportacaoRepositoryInterface;
use App\Domain\Importacao\ImportacaoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Importacao as ImportacaoModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentImportacaoRepository implements ImportacaoRepositoryInterface
{
    public function search(ImportacaoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = ImportacaoModel::query()
            ->with('cliente')
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->tipoImportacao !== '') {
            $query->where('tipo_importacao', $criteria->tipoImportacao);
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (ImportacaoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?ImportacaoEntity
    {
        $model = ImportacaoModel::query()
            ->with('cliente')
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): ImportacaoEntity
    {
        $model = ImportacaoModel::query()->create($data);
        $model->load('cliente');

        return $this->toEntity($model);
    }

    public function storeUploadedFile(string $temporaryPath, string $originalFilename, string $extension, string $subdir): string
    {
        $safeBase = preg_replace('/[^a-zA-Z0-9_-]+/', '_', pathinfo($originalFilename, PATHINFO_FILENAME)) ?: 'import';
        $storedName = $safeBase.'_'.time().'.'.($extension !== '' ? $extension : 'bin');

        $destDir = public_path('files/uploads/'.$subdir);
        if (! is_dir($destDir) && ! mkdir($destDir, 0775, true) && ! is_dir($destDir)) {
            throw new RuntimeException('Não foi possível criar o diretório de upload.');
        }

        $dest = $destDir.DIRECTORY_SEPARATOR.$storedName;
        if (! @rename($temporaryPath, $dest) && ! @copy($temporaryPath, $dest)) {
            throw new RuntimeException('Falha ao gravar arquivo de importação.');
        }

        return $storedName;
    }

    public function tipoImportacaoOptions(): array
    {
        return [
            'beneficiario' => 'Beneficiário',
            'afastado' => 'Afastado',
            'beneficio_previdenciario' => 'Benefício Previdenciário',
            'absenteismo' => 'Absenteísmo',
        ];
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->where('cliente_id', $tenant->clienteId);
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('cliente', function (Builder $q) use ($tenant) {
                $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function toEntity(ImportacaoModel $model): ImportacaoEntity
    {
        return new ImportacaoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            clienteId: $model->cliente_id !== null ? (int) $model->cliente_id : null,
            tipoImportacao: $model->tipo_importacao !== null ? (string) $model->tipo_importacao : null,
            arquivoImportado: $model->arquivo_importado !== null ? (string) $model->arquivo_importado : null,
            avisos: $model->avisos !== null ? (string) $model->avisos : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            usuarioCriadorId: $model->usuario_criador_id !== null ? (int) $model->usuario_criador_id : null,
            status: (int) ($model->status ?? 0),
            clienteNome: $model->cliente->nome ?? null,
        );
    }

    private function toDate(mixed $value): ?DateTimeImmutable
    {
        if ($value === null || $value === '') {
            return null;
        }
        if ($value instanceof \DateTimeInterface) {
            return DateTimeImmutable::createFromInterface($value);
        }

        return new DateTimeImmutable((string) $value);
    }
}
