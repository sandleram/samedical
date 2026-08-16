<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Parametro\Parametro as ParametroEntity;
use App\Domain\Parametro\ParametroRepositoryInterface;
use App\Domain\Parametro\ParametroSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Parametro as ParametroModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentParametroRepository implements ParametroRepositoryInterface
{
    public function search(ParametroSearchCriteria $criteria): PagedResult
    {
        $query = ParametroModel::query();

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

        if ($criteria->tipo !== '') {
            foreach (preg_split('/\s+/', $criteria->tipo) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('tipo', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->valor !== '') {
            foreach (preg_split('/\s+/', $criteria->valor) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('valor', 'like', '%'.$part.'%');
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
            items: array_map(fn (ParametroModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?ParametroEntity
    {
        $model = ParametroModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): ParametroEntity
    {
        $model = ParametroModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): ParametroEntity
    {
        $model = ParametroModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Parâmetro Inexistente');
        }
        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    public function distinctTipos(): array
    {
        return ParametroModel::query()
            ->orderBy('tipo')
            ->distinct()
            ->pluck('tipo', 'tipo')
            ->filter()
            ->all();
    }

    private function toEntity(ParametroModel $model): ParametroEntity
    {
        return new ParametroEntity(
            id: $model->id ? (int) $model->id : null,
            nome: (string) ($model->nome ?? ''),
            tipo: $model->tipo,
            valor: (string) ($model->valor ?? ''),
            ordenacao: $model->ordenacao !== null ? (int) $model->ordenacao : null,
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            dataAtualizacao: $this->toDate($model->data_atualizacao ?? null),
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
