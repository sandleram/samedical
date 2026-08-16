<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\MhPrestador\MhPrestador as MhPrestadorEntity;
use App\Domain\MhPrestador\MhPrestadorRepositoryInterface;
use App\Domain\MhPrestador\MhPrestadorSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\MhPrestador as MhPrestadorModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentMhPrestadorRepository implements MhPrestadorRepositoryInterface
{
    public function search(MhPrestadorSearchCriteria $criteria): PagedResult
    {
        $query = MhPrestadorModel::query();

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('mh_prestador.status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('mh_prestador.id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('nome', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('mh_prestador.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('mh_prestador.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (MhPrestadorModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?MhPrestadorEntity
    {
        $model = MhPrestadorModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): MhPrestadorEntity
    {
        $model = MhPrestadorModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): MhPrestadorEntity
    {
        $model = MhPrestadorModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Prestador Inexistente');
        }
        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    public function optionsAll(): array
    {
        return MhPrestadorModel::query()
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();
    }

    private function toEntity(MhPrestadorModel $model): MhPrestadorEntity
    {
        return new MhPrestadorEntity(
            id: $model->id !== null ? (int) $model->id : null,
            idHubspot: $model->id_hubspot !== null ? (string) $model->id_hubspot : null,
            nome: (string) ($model->nome ?? ''),
            cidade: $model->cidade !== null ? (string) $model->cidade : null,
            estado: $model->estado !== null ? (string) $model->estado : null,
            praca: $model->praca !== null ? (string) $model->praca : null,
            atividade: $model->atividade !== null ? (string) $model->atividade : null,
            descricao: $model->descricao !== null ? (string) $model->descricao : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
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
