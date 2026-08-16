<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Beneficio\Beneficio as BeneficioEntity;
use App\Domain\Beneficio\BeneficioRepositoryInterface;
use App\Domain\Beneficio\BeneficioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Beneficio as BeneficioModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentBeneficioRepository implements BeneficioRepositoryInterface
{
    public function search(BeneficioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = BeneficioModel::query()->with(['operadora', 'tipoBeneficio', 'cliente'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('beneficio.status', '<', 2);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('beneficio.id', (int) $criteria->id);
        }

        if ($criteria->descricao !== '') {
            foreach (preg_split('/\s+/', $criteria->descricao) ?: [] as $part) {
                if ($part !== '') {
                    $query->where('descricao', 'like', '%'.$part.'%');
                }
            }
        }

        if ($criteria->status !== '') {
            $query->where('beneficio.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('beneficio.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (BeneficioModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?BeneficioEntity
    {
        $model = BeneficioModel::query()->with(['operadora', 'tipoBeneficio', 'cliente'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): BeneficioEntity
    {
        $model = BeneficioModel::query()->create($data);
        $model->load(['operadora', 'tipoBeneficio', 'cliente']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): BeneficioEntity
    {
        $model = BeneficioModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Beneficio Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['operadora', 'tipoBeneficio', 'cliente']);

        return $this->toEntity($model);
    }

    public function formOptions(TenantScope $tenant, int $clienteId): array
    {
        $operadoras = \App\Models\Operadora::query()
            ->where('status', '<', 2)
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();
        $tipos = \App\Models\TipoBeneficio::query()
            ->where('status', 1)
            ->orderBy('descricao')
            ->pluck('descricao', 'id')
            ->all();

        return [
            'operadoras' => $operadoras,
            'tiposBeneficio' => $tipos,
        ];
    }

    public function optionsForTenant(TenantScope $tenant): array
    {
        $query = BeneficioModel::query()->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->where('status', '<', 2)
            ->orderBy('descricao');

        return $query->pluck('descricao', 'id')->all();
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->where('cliente_id', $tenant->clienteId);
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('cliente', function (Builder $q) use ($tenant) {
                $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function toEntity(BeneficioModel $model): BeneficioEntity
    {
        return new BeneficioEntity(
            id: $model->id !== null ? (int) $model->id : null,
            descricao: (string) ($model->descricao ?? ''),
            breakeven: $model->breakeven !== null ? (int) $model->breakeven : null,
            contrato: $model->contrato !== null ? (string) $model->contrato : null,
            clienteId: $model->cliente_id !== null ? (int) $model->cliente_id : null,
            operadoraId: $model->operadora_id !== null ? (int) $model->operadora_id : null,
            tipoBeneficioId: $model->tipo_beneficio_id !== null ? (int) $model->tipo_beneficio_id : null,
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            dataAtualizacao: $this->toDate($model->data_atualizacao ?? null),
            dataCancelamento: $this->toDate($model->data_cancelamento ?? null),
            status: (int) ($model->status ?? 0),
            operadoraNome: $model->operadora->nome ?? null,
            tipoBeneficioDescricao: $model->tipoBeneficio->descricao ?? null,
            clienteNome: $model->cliente->nome ?? null,
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
