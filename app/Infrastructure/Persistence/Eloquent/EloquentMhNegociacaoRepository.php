<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\MhNegociacao\MhNegociacao as MhNegociacaoEntity;
use App\Domain\MhNegociacao\MhNegociacaoRepositoryInterface;
use App\Domain\MhNegociacao\MhNegociacaoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\MhNegociacao as MhNegociacaoModel;
use App\Models\MhPrestador;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentMhNegociacaoRepository implements MhNegociacaoRepositoryInterface
{
    public function search(MhNegociacaoSearchCriteria $criteria): PagedResult
    {
        $query = MhNegociacaoModel::query()->with('prestador');

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('mh_negociacao.status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('mh_negociacao.id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            $query->whereHas('prestador', function (Builder $pq) use ($criteria) {
                foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                    if ($part !== '') {
                        $pq->where('nome', 'like', '%'.$part.'%');
                    }
                }
            });
        }

        if ($criteria->status !== '') {
            $query->where('mh_negociacao.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('mh_negociacao.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (MhNegociacaoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?MhNegociacaoEntity
    {
        $model = MhNegociacaoModel::query()->with('prestador')->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): MhNegociacaoEntity
    {
        $model = MhNegociacaoModel::query()->create($data);
        $model->load('prestador');

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): MhNegociacaoEntity
    {
        $model = MhNegociacaoModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Negociação Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load('prestador');

        return $this->toEntity($model);
    }

    public function formPrestadorOptions(): array
    {
        return MhPrestador::query()
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();
    }

    private function toEntity(MhNegociacaoModel $model): MhNegociacaoEntity
    {
        return new MhNegociacaoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            mhPrestadorId: $model->mh_prestador_id !== null ? (int) $model->mh_prestador_id : null,
            tipoNegocio: $model->tipo_negocio !== null ? (int) $model->tipo_negocio : null,
            usuarioNegociadorId: $model->usuario_negociador_id !== null ? (int) $model->usuario_negociador_id : null,
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            status: (int) ($model->status ?? 0),
            prestadorNome: $model->prestador->nome ?? null,
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
