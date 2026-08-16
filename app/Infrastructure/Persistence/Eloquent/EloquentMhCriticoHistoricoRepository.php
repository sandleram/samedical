<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\MhCritico\MhCritico as MhCriticoEntity;
use App\Domain\MhCritico\MhCriticoRepositoryInterface;
use App\Domain\MhCriticoHistorico\MhCriticoHistorico as MhCriticoHistoricoEntity;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoRepositoryInterface;
use App\Domain\MhCriticoHistorico\MhCriticoHistoricoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\MhCritico as MhCriticoModel;
use App\Models\MhCriticoHistorico as MhCriticoHistoricoModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentMhCriticoHistoricoRepository implements MhCriticoHistoricoRepositoryInterface
{
    public function __construct(
        private readonly MhCriticoRepositoryInterface $criticoRepository,
    ) {}

    public function search(MhCriticoHistoricoSearchCriteria $criteria): PagedResult
    {
        $query = MhCriticoHistoricoModel::query()
            ->with(['critico.prestador'])
            ->where('mh_critico_id', $criteria->mhCriticoId);

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('id', (int) $criteria->id);
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (MhCriticoHistoricoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $mhCriticoId, int $id): ?MhCriticoHistoricoEntity
    {
        $model = MhCriticoHistoricoModel::query()
            ->with(['critico.prestador'])
            ->where('mh_critico_id', $mhCriticoId)
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findCritico(int $mhCriticoId): ?MhCriticoEntity
    {
        return $this->criticoRepository->findById($mhCriticoId);
    }

    public function criticoExists(int $mhCriticoId): bool
    {
        return MhCriticoModel::query()->whereKey($mhCriticoId)->exists();
    }

    public function create(array $data): MhCriticoHistoricoEntity
    {
        $model = MhCriticoHistoricoModel::query()->create($data);
        $model->load(['critico.prestador']);

        return $this->toEntity($model);
    }

    public function update(int $mhCriticoId, int $id, array $data): MhCriticoHistoricoEntity
    {
        $model = MhCriticoHistoricoModel::query()
            ->where('mh_critico_id', $mhCriticoId)
            ->find($id);
        if (! $model) {
            throw new RuntimeException('Crítico Histórico  Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['critico.prestador']);

        return $this->toEntity($model);
    }

    private function toEntity(MhCriticoHistoricoModel $model): MhCriticoHistoricoEntity
    {
        return new MhCriticoHistoricoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            mhCriticoId: (int) $model->mh_critico_id,
            ciclo: $model->ciclo !== null ? (int) $model->ciclo : null,
            statusCiclo: $model->status_ciclo !== null ? (int) $model->status_ciclo : null,
            descricao: $model->descricao !== null ? (string) $model->descricao : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            status: (int) ($model->status ?? 0),
            criticoPrestadorNome: $model->critico?->prestador?->nome,
            criticoPrestadorCidade: $model->critico?->prestador?->cidade,
            criticoPrestadorEstado: $model->critico?->prestador?->estado,
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
