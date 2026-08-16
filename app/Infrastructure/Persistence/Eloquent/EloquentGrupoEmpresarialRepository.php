<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\GrupoEmpresarial\GrupoEmpresarial as GrupoEmpresarialEntity;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialRepositoryInterface;
use App\Domain\GrupoEmpresarial\GrupoEmpresarialSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\GrupoEmpresarial as GrupoEmpresarialModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentGrupoEmpresarialRepository implements GrupoEmpresarialRepositoryInterface
{
    public function search(GrupoEmpresarialSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = GrupoEmpresarialModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', '<', 2);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('nome', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        $items = array_map(
            fn (GrupoEmpresarialModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?GrupoEmpresarialEntity
    {
        $model = GrupoEmpresarialModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): GrupoEmpresarialEntity
    {
        $model = GrupoEmpresarialModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): GrupoEmpresarialEntity
    {
        $model = GrupoEmpresarialModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        if (! $model) {
            throw new RuntimeException('Grupo Empresarial Inexistente');
        }

        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    /**
     * Root: TenantScope sem grupo → sem filtro (vê todos).
     * Demais: filtra pelo grupo da sessão quando informado.
     */
    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->grupoEmpresarialId) {
            $query->where($query->getModel()->getTable().'.id', $tenant->grupoEmpresarialId);
        }
    }

    private function toEntity(GrupoEmpresarialModel $model): GrupoEmpresarialEntity
    {
        return new GrupoEmpresarialEntity(
            id: $model->id ? (int) $model->id : null,
            nome: (string) ($model->nome ?? ''),
            imgLogo: $model->img_logo,
            bi: $model->bi,
            cor: $model->cor,
            status: (int) ($model->status ?? 0),
            dataCadastro: $this->toDateTime($model->data_cadastro),
            dataCancelamento: $this->toDateTime($model->data_cancelamento),
        );
    }

    private function toDateTime(mixed $value): ?DateTimeImmutable
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
