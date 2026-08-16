<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Perfil\Perfil as PerfilEntity;
use App\Domain\Perfil\PerfilRepositoryInterface;
use App\Domain\Perfil\PerfilSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Perfil as PerfilModel;
use App\Models\PerfilModulo;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;
use RuntimeException;

final class EloquentPerfilRepository implements PerfilRepositoryInterface
{
    public function search(PerfilSearchCriteria $criteria): PagedResult
    {
        $query = PerfilModel::query();

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

        $items = array_map(
            fn (PerfilModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?PerfilEntity
    {
        $model = PerfilModel::query()->with('perfilModulos')->find($id);

        return $model ? $this->toEntity($model, withModulos: true) : null;
    }

    public function save(array $data, array $perfilModulos, ?int $existingId): PerfilEntity
    {
        $id = DB::transaction(function () use ($data, $perfilModulos, $existingId) {
            if ($existingId !== null) {
                $model = PerfilModel::query()->find($existingId);
                if (! $model) {
                    throw new RuntimeException('Perfil Inexistente');
                }
                $model->fill($data);
                $model->save();
                $id = (int) $model->id;
            } else {
                $created = PerfilModel::query()->create($data);
                $id = (int) $created->id;
            }

            $now = $data['data_atualizacao'] ?? $data['data_cadastro'] ?? now();

            foreach ($perfilModulos as $moduloId => $row) {
                $moduloId = (int) $moduloId;
                $permId = isset($row['id']) && $row['id'] !== '' ? (int) $row['id'] : null;
                $level = (int) ($row['permissao'] ?? 0);

                if ($permId) {
                    $pm = PerfilModulo::query()->find($permId);
                    if (! $pm) {
                        throw new RuntimeException('PerfilModulo inexistente');
                    }
                    $pm->fill([
                        'modulo_id' => $moduloId,
                        'perfil_id' => $id,
                        'permissao' => $level,
                        'data_atualizacao' => $now,
                    ]);
                    $pm->save();
                } else {
                    PerfilModulo::query()->create([
                        'modulo_id' => $moduloId,
                        'perfil_id' => $id,
                        'permissao' => $level,
                        'status' => 1,
                        'data_cadastro' => $now,
                    ]);
                }
            }

            return $id;
        });

        $entity = $this->findById($id);
        if (! $entity) {
            throw new RuntimeException('Perfil Inexistente');
        }

        return $entity;
    }

    public function optionsActive(bool $includeRootPerfil): array
    {
        $query = PerfilModel::query()->where('status', 1)->orderBy('nome');
        if (! $includeRootPerfil) {
            $query->where('id', '>', 1);
        }

        return $query->pluck('nome', 'id')->all();
    }

    private function toEntity(PerfilModel $model, bool $withModulos = false): PerfilEntity
    {
        $perfilModulos = [];
        if ($withModulos) {
            foreach ($model->perfilModulos as $pm) {
                $perfilModulos[] = (object) [
                    'id' => $pm->id !== null ? (int) $pm->id : null,
                    'modulo_id' => (int) $pm->modulo_id,
                    'permissao' => (int) $pm->permissao,
                ];
            }
        }

        return new PerfilEntity(
            id: $model->id ? (int) $model->id : null,
            nome: (string) ($model->nome ?? ''),
            tipo: (int) ($model->tipo ?? 0),
            status: (int) ($model->status ?? 0),
            dataCadastro: $this->toImmutable($model->data_cadastro),
            dataAtualizacao: $this->toImmutable($model->data_atualizacao),
            perfilModulos: $perfilModulos,
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
