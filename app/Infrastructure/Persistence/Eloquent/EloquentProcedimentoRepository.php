<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Procedimento\Procedimento as ProcedimentoEntity;
use App\Domain\Procedimento\ProcedimentoRepositoryInterface;
use App\Domain\Procedimento\ProcedimentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Procedimento as ProcedimentoModel;
use DateTimeImmutable;
use RuntimeException;

final class EloquentProcedimentoRepository implements ProcedimentoRepositoryInterface
{
    public function search(ProcedimentoSearchCriteria $criteria): PagedResult
    {
        $query = ProcedimentoModel::query();

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('procedimento.status', '<', 2);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('procedimento.id', (int) $criteria->id);
        }

        if ($criteria->codProcedimento !== '') {
            foreach (preg_split('/\s+/', $criteria->codProcedimento) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('cod_procedimento', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->dsProcedimento !== '') {
            foreach (preg_split('/\s+/', $criteria->dsProcedimento) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('ds_procedimento', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('procedimento.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('procedimento.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (ProcedimentoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?ProcedimentoEntity
    {
        $model = ProcedimentoModel::query()->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): ProcedimentoEntity
    {
        $model = ProcedimentoModel::query()->create($data);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): ProcedimentoEntity
    {
        $model = ProcedimentoModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Procedimento Inexistente');
        }
        $model->fill($data);
        $model->save();

        return $this->toEntity($model);
    }

    private function toEntity(ProcedimentoModel $model): ProcedimentoEntity
    {
        return new ProcedimentoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            codProcedimento: $model->cod_procedimento !== null ? (string) $model->cod_procedimento : null,
            dsProcedimento: (string) ($model->ds_procedimento ?? ''),
            tipoProcedimento: $model->tipo_procedimento !== null ? (string) $model->tipo_procedimento : null,
            grupo: $model->Grupo !== null ? (string) $model->Grupo : null,
            subgrupo: $model->Subgrupo !== null ? (string) $model->Subgrupo : null,
            grupoDeExames: $model->{'Grupo de Exames'} !== null ? (string) $model->{'Grupo de Exames'} : null,
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
