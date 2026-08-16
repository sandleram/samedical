<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\TipoBeneficio\TipoBeneficio as TipoBeneficioEntity;
use App\Domain\TipoBeneficio\TipoBeneficioRepositoryInterface;
use App\Domain\TipoBeneficio\TipoBeneficioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\TipoBeneficio as TipoBeneficioModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentTipoBeneficioRepository implements TipoBeneficioRepositoryInterface
{
    public function search(TipoBeneficioSearchCriteria $criteria): PagedResult
    {
        $query = TipoBeneficioModel::query();

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('tipo_beneficio.status', '<', 2);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('tipo_beneficio.id', (int) $criteria->id);
        }

        if ($criteria->descricao !== '') {
            foreach (preg_split('/\s+/', $criteria->descricao) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('descricao', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('tipo_beneficio.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('tipo_beneficio.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (TipoBeneficioModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?TipoBeneficioEntity
    {
        $model = TipoBeneficioModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): TipoBeneficioEntity
    {
        $model = TipoBeneficioModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): TipoBeneficioEntity
    {
        $model = TipoBeneficioModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Tipo Beneficio Inexistente');
        }
        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    public function optionsActive(): array
    {
        return TipoBeneficioModel::query()
            ->where('status', 1)
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->all();
    }

    private function toEntity(TipoBeneficioModel $model): TipoBeneficioEntity
    {
        return new TipoBeneficioEntity(
            id: $model->id !== null ? (int) $model->id : null,
            descricao: (string) ($model->descricao ?? ''),
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            dataAtualizacao: $this->toDate($model->data_atualizacao ?? null),
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
