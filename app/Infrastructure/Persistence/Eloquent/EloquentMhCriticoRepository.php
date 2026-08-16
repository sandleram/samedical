<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\MhCritico\MhCritico as MhCriticoEntity;
use App\Domain\MhCritico\MhCriticoRepositoryInterface;
use App\Domain\MhCritico\MhCriticoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\MhCritico as MhCriticoModel;
use App\Models\MhPrestador;
use DateTimeImmutable;
use RuntimeException;

final class EloquentMhCriticoRepository implements MhCriticoRepositoryInterface
{
    public function searchPrincipals(MhCriticoSearchCriteria $criteria): PagedResult
    {
        $query = MhCriticoModel::query()
            ->with(['prestador', 'historicos'])
            ->where('principal', 1);

        $this->applyFilters($query, $criteria);

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (MhCriticoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function listSubsidiaries(MhCriticoSearchCriteria $criteria): array
    {
        $query = MhCriticoModel::query()
            ->with(['prestador', 'historicos'])
            ->where('principal', 0);

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', 1);
        }

        $rowsSub = [];
        foreach ($query->get() as $sub) {
            $key = (int) $sub->mh_prestador_principal_id;
            $rowsSub[$key][] = $this->toEntity($sub);
        }

        return $rowsSub;
    }

    public function findById(int $id): ?MhCriticoEntity
    {
        $model = MhCriticoModel::query()
            ->with(['prestador', 'prestadorPrincipal', 'historicos'])
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function exists(int $id): bool
    {
        return MhCriticoModel::query()->whereKey($id)->exists();
    }

    public function create(array $data): MhCriticoEntity
    {
        $model = MhCriticoModel::query()->create($data);
        $model->load(['prestador', 'prestadorPrincipal', 'historicos']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): MhCriticoEntity
    {
        $model = MhCriticoModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Crítico Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['prestador', 'prestadorPrincipal', 'historicos']);

        return $this->toEntity($model);
    }

    public function formOptions(): array
    {
        $usedIds = MhCriticoModel::query()
            ->select(['mh_prestador_id', 'mh_prestador_principal_id'])
            ->get()
            ->flatMap(fn ($r) => [$r->mh_prestador_id, $r->mh_prestador_principal_id])
            ->unique()
            ->filter()
            ->values()
            ->all();

        $listPrestadorAll = MhPrestador::query()
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();

        $listPrestadorSemUsados = MhPrestador::query()
            ->when(count($usedIds) > 0, fn ($q) => $q->whereNotIn('id', $usedIds))
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();

        return [
            'listPrestadorAll' => $listPrestadorAll,
            'listPrestadorSemUsados' => $listPrestadorSemUsados,
        ];
    }

    private function applyFilters($query, MhCriticoSearchCriteria $criteria): void
    {
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
    }

    private function toEntity(MhCriticoModel $model): MhCriticoEntity
    {
        return new MhCriticoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            mhPrestadorId: $model->mh_prestador_id !== null ? (int) $model->mh_prestador_id : null,
            mhPrestadorPrincipalId: $model->mh_prestador_principal_id !== null ? (int) $model->mh_prestador_principal_id : null,
            principal: $model->principal !== null ? (int) $model->principal : null,
            nome: $model->nome !== null ? (string) $model->nome : null,
            opcao: $model->opcao !== null ? (int) $model->opcao : null,
            ciclo: $model->ciclo !== null ? (int) $model->ciclo : null,
            statusCiclo: $model->status_ciclo !== null ? (int) $model->status_ciclo : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            dataAtualizacao: $this->toDate($model->data_atualizacao ?? null),
            usuarioId: $model->usuario_id !== null ? (string) $model->usuario_id : null,
            usuarioAtualizacaoId: $model->usuario_atualizacao_id !== null ? (string) $model->usuario_atualizacao_id : null,
            status: (int) ($model->status ?? 0),
            prestadorNome: $model->prestador->nome ?? null,
            prestadorCidade: $model->prestador->cidade ?? null,
            prestadorEstado: $model->prestador->estado ?? null,
            prestadorPrincipalNome: $model->prestadorPrincipal->nome ?? null,
            historicosCount: $model->relationLoaded('historicos') ? $model->historicos->count() : 0,
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
