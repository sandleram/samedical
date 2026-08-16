<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Modulo\Modulo as ModuloEntity;
use App\Domain\Modulo\ModuloRepositoryInterface;
use App\Domain\Modulo\ModuloSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Modulo as ModuloModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentModuloRepository implements ModuloRepositoryInterface
{
    public function search(ModuloSearchCriteria $criteria): PagedResult
    {
        $query = ModuloModel::query()->with('parent');

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->moduloId !== '' && $criteria->moduloId !== null) {
            $query->where('modulo_id', (int) $criteria->moduloId);
        }

        if ($criteria->nome !== '') {
            foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('nome', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->controller !== '') {
            foreach (preg_split('/\s+/', $criteria->controller) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('controller', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query
            ->orderBy('modulo_id')
            ->orderBy('order')
            ->paginate(
                perPage: $criteria->perPage,
                page: $criteria->page,
            );

        $items = array_map(
            fn (ModuloModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?ModuloEntity
    {
        $model = ModuloModel::query()->with('parent')->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): ModuloEntity
    {
        $model = ModuloModel::query()->create($data);
        $model->load('parent');

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): ModuloEntity
    {
        $model = ModuloModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Módulo Inexistente');
        }

        $model->fill($data);
        $model->save();
        $model->load('parent');

        return $this->toEntity($model);
    }

    public function parentOptions(): array
    {
        $list = ModuloModel::query()
            ->where('modulo_id', 0)
            ->orderBy('order')
            ->pluck('nome', 'id')
            ->all();

        return ['' => 'Módulo Pai...', '0' => '— Raiz —'] + $list;
    }

    public function listActiveOrdered(): array
    {
        return ModuloModel::query()
            ->where('status', 1)
            ->orderBy('order')
            ->get()
            ->map(fn (ModuloModel $model) => $this->toEntity($model))
            ->all();
    }

    private function toEntity(ModuloModel $model): ModuloEntity
    {
        return new ModuloEntity(
            id: $model->id ? (int) $model->id : null,
            moduloId: (int) ($model->modulo_id ?? 0),
            nome: (string) ($model->nome ?? ''),
            controller: (string) ($model->controller ?? ''),
            order: (int) ($model->order ?? 0),
            menu: (int) ($model->menu ?? 0),
            icon: $model->icon,
            status: (int) ($model->status ?? 0),
            dataCadastro: $this->toImmutable($model->data_cadastro),
            dataAtualizacao: $this->toImmutable($model->data_atualizacao),
            parentNome: $model->parent?->nome,
        );
    }

    private function toImmutable(mixed $value): ?DateTimeImmutable
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
