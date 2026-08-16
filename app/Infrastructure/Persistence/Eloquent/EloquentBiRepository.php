<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Bi\Bi as BiEntity;
use App\Domain\Bi\BiRepositoryInterface;
use App\Domain\Bi\BiSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Bi as BiModel;
use App\Models\Cliente;
use App\Models\GrupoEmpresarial;
use App\Models\UsuarioBi;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentBiRepository implements BiRepositoryInterface
{
    public function search(BiSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = BiModel::query()->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->titulo !== '') {
            $query->where('titulo', 'like', '%'.$criteria->titulo.'%');
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query->orderBy('ordem')->orderBy('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (BiModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?BiEntity
    {
        $model = BiModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): BiEntity
    {
        $model = BiModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): BiEntity
    {
        $model = BiModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);
        if (! $model) {
            throw new RuntimeException('Bi Inexistente');
        }
        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    public function listDashboardsForUsuario(int $usuarioId, TenantScope $tenant): array
    {
        $list = [];

        $append = function (?int $clienteFilter) use ($usuarioId, $tenant, &$list): void {
            $rows = UsuarioBi::query()
                ->where('usuario_id', $usuarioId)
                ->whereHas('bi', function (Builder $q) use ($tenant, $clienteFilter) {
                    $q->where('status', 1);
                    if ($tenant->grupoEmpresarialId) {
                        $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
                    }
                    if ($clienteFilter === null) {
                        $q->whereNull('cliente_id');
                    } else {
                        $q->where('cliente_id', $clienteFilter);
                    }
                })
                ->with('bi')
                ->get()
                ->sortBy(fn (UsuarioBi $ub) => (int) ($ub->bi?->ordem ?? 0));

            foreach ($rows as $row) {
                if (! $row->bi) {
                    continue;
                }
                $list[] = [
                    'titulo' => $row->bi->titulo,
                    'subtitulo' => $row->bi->subtitulo,
                    'link' => $row->bi->link,
                ];
            }
        };

        $append(null);
        if ($tenant->clienteId) {
            $append($tenant->clienteId);
        }

        return $list;
    }

    public function gerencialUrl(TenantScope $tenant): string
    {
        if (! $tenant->grupoEmpresarialId) {
            return '';
        }

        return (string) (GrupoEmpresarial::query()->where('id', $tenant->grupoEmpresarialId)->value('bi') ?? '');
    }

    public function medicoUrl(TenantScope $tenant): string
    {
        if (! $tenant->clienteId) {
            return '';
        }

        return (string) (Cliente::query()->where('id', $tenant->clienteId)->value('bi_medico') ?? '');
    }

    public function rhUrl(TenantScope $tenant): string
    {
        if (! $tenant->clienteId) {
            return '';
        }

        return (string) (Cliente::query()->where('id', $tenant->clienteId)->value('bi_rh') ?? '');
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->grupoEmpresarialId) {
            $query->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
        }

        if ($tenant->clienteId) {
            $query->where(function (Builder $q) use ($tenant) {
                $q->whereNull('cliente_id')->orWhere('cliente_id', $tenant->clienteId);
            });
        }
    }

    private function toEntity(BiModel $model): BiEntity
    {
        return new BiEntity(
            id: $model->id !== null ? (int) $model->id : null,
            grupoEmpresarialId: $model->grupo_empresarial_id !== null ? (int) $model->grupo_empresarial_id : null,
            clienteId: $model->cliente_id !== null ? (int) $model->cliente_id : null,
            titulo: $model->titulo !== null ? (string) $model->titulo : null,
            subtitulo: $model->subtitulo !== null ? (string) $model->subtitulo : null,
            link: $model->link !== null ? (string) $model->link : null,
            observacao: $model->observacao !== null ? (string) $model->observacao : null,
            ordem: $model->ordem !== null ? (int) $model->ordem : null,
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
