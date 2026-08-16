<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Operadora\Operadora as OperadoraEntity;
use App\Domain\Operadora\OperadoraRepositoryInterface;
use App\Domain\Operadora\OperadoraSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Operadora as OperadoraModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentOperadoraRepository implements OperadoraRepositoryInterface
{
    public function search(OperadoraSearchCriteria $criteria): PagedResult
    {
        $query = OperadoraModel::query();

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', 1);
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

        return new PagedResult(
            items: array_map(fn (OperadoraModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?OperadoraEntity
    {
        $model = OperadoraModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): OperadoraEntity
    {
        $model = OperadoraModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): OperadoraEntity
    {
        $model = OperadoraModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Operadora Inexistente');
        }
        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    public function options(bool $withPlaceholder = true): array
    {
        $items = OperadoraModel::query()
            ->where('status', 1)
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();

        return $withPlaceholder
            ? ['' => 'Operadora...'] + $items
            : ['' => ''] + $items;
    }

    private function toEntity(OperadoraModel $model): OperadoraEntity
    {
        return new OperadoraEntity(
            id: $model->id ? (int) $model->id : null,
            nome: (string) ($model->nome ?? ''),
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            dataCancelamento: $this->toDate($model->data_cancelamento ?? null),
            status: (int) ($model->status ?? 0),
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
