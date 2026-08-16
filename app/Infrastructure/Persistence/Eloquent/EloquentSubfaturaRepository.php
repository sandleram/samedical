<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Subfatura\Subfatura as SubfaturaEntity;
use App\Domain\Subfatura\SubfaturaRepositoryInterface;
use App\Domain\Subfatura\SubfaturaSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Subfatura as SubfaturaModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentSubfaturaRepository implements SubfaturaRepositoryInterface
{
    public function search(SubfaturaSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = SubfaturaModel::query()->with(['beneficio'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('subfatura.status', '<', 2);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('subfatura.id', (int) $criteria->id);
        }

        if ($criteria->descricao !== '') {
            foreach (preg_split('/\s+/', $criteria->descricao) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('descricao', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->codigo !== '') {
            foreach (preg_split('/\s+/', $criteria->codigo) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('codigo', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('subfatura.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('subfatura.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (SubfaturaModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?SubfaturaEntity
    {
        $model = SubfaturaModel::query()->with(['beneficio'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): SubfaturaEntity
    {
        $model = SubfaturaModel::query()->create($data);
        $model->load(['beneficio']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): SubfaturaEntity
    {
        $model = SubfaturaModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Subfatura Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['beneficio']);

        return $this->toEntity($model);
    }

    public function formOptions(TenantScope $tenant, int $clienteId): array
    {
        $beneficios = \App\Models\Beneficio::query()
            ->where('cliente_id', $clienteId)
            ->where('status', '<', 2)
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->all();

        return ['beneficios' => $beneficios];
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->whereHas('beneficio', function (Builder $q) use ($tenant) {
                $q->where('cliente_id', $tenant->clienteId);
            });
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('beneficio.cliente', function (Builder $q) use ($tenant) {
                $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function toEntity(SubfaturaModel $model): SubfaturaEntity
    {
        return new SubfaturaEntity(
            id: $model->id !== null ? (int) $model->id : null,
            beneficioId: $model->beneficio_id !== null ? (int) $model->beneficio_id : null,
            descricao: (string) ($model->descricao ?? ''),
            codigo: (string) ($model->codigo ?? ''),
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            dataCancelamento: $this->toDate($model->data_cancelamento ?? null),
            status: (int) ($model->status ?? 0),
            beneficioDescricao: $model->beneficio->descricao ?? null,
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
