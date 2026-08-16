<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Plano\Plano as PlanoEntity;
use App\Domain\Plano\PlanoRepositoryInterface;
use App\Domain\Plano\PlanoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Models\Plano as PlanoModel;
use App\Models\TipoBeneficio;
use DateTimeImmutable;
use RuntimeException;

final class EloquentPlanoRepository implements PlanoRepositoryInterface
{
    public function search(PlanoSearchCriteria $criteria): PagedResult
    {
        $query = PlanoModel::query()
            ->with(['operadora', 'tipoBeneficio']);

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

        if ($criteria->tipoBeneficioId !== '') {
            $query->where('tipo_beneficio_id', (int) $criteria->tipoBeneficioId);
        }

        if ($criteria->operadoraId !== '') {
            $query->where('operadora_id', (int) $criteria->operadoraId);
        }

        if ($criteria->status !== '') {
            $query->where('status', (int) $criteria->status);
        }

        $paginator = $query
            ->leftJoin('operadora', 'plano.operadora_id', '=', 'operadora.id')
            ->select('plano.*')
            ->orderBy('operadora.nome')
            ->orderBy('plano.ordem')
            ->paginate(
                perPage: $criteria->perPage,
                page: $criteria->page,
            );

        return new PagedResult(
            items: array_map(fn (PlanoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id): ?PlanoEntity
    {
        $model = PlanoModel::query()
            ->with(['operadora', 'tipoBeneficio'])
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): PlanoEntity
    {
        $model = PlanoModel::query()->create($data);
        $model->load(['operadora', 'tipoBeneficio']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data): PlanoEntity
    {
        $model = PlanoModel::query()->find($id);
        if (! $model) {
            throw new RuntimeException('Plano Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['operadora', 'tipoBeneficio']);

        return $this->toEntity($model);
    }

    public function nextOrdem(?int $clienteId, ?int $operadoraId, ?int $tipoBeneficioId): int
    {
        $max = PlanoModel::query()
            ->when($clienteId !== null, fn ($q) => $q->where('cliente_id', $clienteId))
            ->when($operadoraId !== null, fn ($q) => $q->where('operadora_id', $operadoraId))
            ->when($tipoBeneficioId !== null, fn ($q) => $q->where('tipo_beneficio_id', $tipoBeneficioId))
            ->where('status', '<', 2)
            ->max('ordem');

        return ((int) $max) + 1;
    }

    public function tipoBeneficioOptions(bool $withPlaceholder = true): array
    {
        $items = TipoBeneficio::query()
            ->where('status', 1)
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->all();

        return $withPlaceholder
            ? ['' => 'Tipo de Beneficio...'] + $items
            : ['' => ''] + $items;
    }

    private function toEntity(PlanoModel $model): PlanoEntity
    {
        return new PlanoEntity(
            id: $model->id ? (int) $model->id : null,
            nome: (string) ($model->nome ?? ''),
            codigoOperadora: $model->codigo_operadora,
            operadoraId: $model->operadora_id !== null ? (int) $model->operadora_id : null,
            tipoBeneficioId: $model->tipo_beneficio_id !== null ? (int) $model->tipo_beneficio_id : null,
            clienteId: $model->cliente_id !== null ? (int) $model->cliente_id : null,
            ordem: $model->ordem !== null ? (int) $model->ordem : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            status: (int) ($model->status ?? 0),
            operadoraNome: $model->operadora?->nome,
            tipoBeneficioDescricao: $model->tipoBeneficio?->descricao,
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
