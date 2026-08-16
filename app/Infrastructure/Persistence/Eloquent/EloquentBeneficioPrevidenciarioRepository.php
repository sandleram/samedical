<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciario as BeneficioPrevidenciarioEntity;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioRepositoryInterface;
use App\Domain\BeneficioPrevidenciario\BeneficioPrevidenciarioSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\BeneficioPrevidenciario as BeneficioPrevidenciarioModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentBeneficioPrevidenciarioRepository implements BeneficioPrevidenciarioRepositoryInterface
{
    public function search(BeneficioPrevidenciarioSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = BeneficioPrevidenciarioModel::query()->with(['beneficiario', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('beneficio_previdenciario.status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('beneficio_previdenciario.id', (int) $criteria->id);
        }

        if ($criteria->nome !== '') {
            $query->whereHas('beneficiario', function (Builder $bq) use ($criteria) {
                foreach (preg_split('/\s+/', $criteria->nome) ?: [] as $part) {
                    if ($part !== '') {
                        $bq->where('nome', 'like', '%'.$part.'%');
                    }
                }
            });
        }

        if ($criteria->nb !== '') {
            $query->where('nb', $criteria->nb);
        }

        if ($criteria->status !== '') {
            $query->where('beneficio_previdenciario.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('beneficio_previdenciario.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (BeneficioPrevidenciarioModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?BeneficioPrevidenciarioEntity
    {
        $model = BeneficioPrevidenciarioModel::query()->with(['beneficiario', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): BeneficioPrevidenciarioEntity
    {
        $model = BeneficioPrevidenciarioModel::query()->create($data);
        $model->load(['beneficiario', 'empresa']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): BeneficioPrevidenciarioEntity
    {
        $model = BeneficioPrevidenciarioModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Benefício Previdenciário Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['beneficiario', 'empresa']);

        return $this->toEntity($model);
    }

    public function formOptions(TenantScope $tenant, int $clienteId): array
    {
        $beneficiarios = \App\Models\Beneficiario::query()
            ->tap(fn (Builder $q) => $this->applyBeneficiarioTenant($q, $tenant))
            ->where('status', 1)
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();

        $empresas = \App\Models\Empresa::query()
            ->where('cliente_id', $clienteId)
            ->orderBy('razao_social')
            ->get()
            ->mapWithKeys(function ($e) {
                $label = trim(($e->razao_social ?: $e->nome_fantasia ?: $e->nome ?: 'Empresa').' '.($e->cnpj ?: ''));

                return [$e->id => $label];
            })
            ->all();

        $especies = \Illuminate\Support\Facades\DB::table('especie_bp')
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();

        return [
            'beneficiarios' => $beneficiarios,
            'empresas' => $empresas,
            'especies' => $especies,
        ];
    }

    public function beneficiarioAllowed(int $beneficiarioId, TenantScope $tenant, int $clienteId): bool
    {
        return \App\Models\Beneficiario::query()
            ->tap(fn (Builder $q) => $this->applyBeneficiarioTenant($q, $tenant))
            ->where('id', $beneficiarioId)
            ->where('cliente_id', $clienteId)
            ->exists();
    }

    private function applyBeneficiarioTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->where('cliente_id', $tenant->clienteId);
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('cliente', function (Builder $q) use ($tenant) {
                $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->whereHas('beneficiario', function (Builder $q) use ($tenant) {
                $q->where('cliente_id', $tenant->clienteId);
            });
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('beneficiario.cliente', function (Builder $q) use ($tenant) {
                $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function toEntity(BeneficioPrevidenciarioModel $model): BeneficioPrevidenciarioEntity
    {
        return new BeneficioPrevidenciarioEntity(
            id: $model->id !== null ? (int) $model->id : null,
            beneficiarioId: $model->beneficiario_id !== null ? (int) $model->beneficiario_id : null,
            empresaId: $model->empresa_id !== null ? (int) $model->empresa_id : null,
            especieBpId: $model->especie_bp_id !== null ? (int) $model->especie_bp_id : null,
            nb: $model->nb !== null ? (int) $model->nb : null,
            nit: $model->nit !== null ? (int) $model->nit : null,
            numRequerimento: $model->num_requerimento !== null ? (int) $model->num_requerimento : null,
            especie: $model->especie !== null ? (string) $model->especie : null,
            situacao: $model->situacao !== null ? (string) $model->situacao : null,
            dataInicio: $this->toDate($model->data_inicio ?? null),
            dataCessacao: $this->toDate($model->data_cessacao ?? null),
            dataProximaPericia: $this->toDate($model->data_proxima_pericia ?? null),
            dataEntradaRequerimento: $this->toDate($model->data_entrada_requerimento ?? null),
            conclusaoPericiaMedica: $model->conclusao_pericia_medica !== null ? (string) $model->conclusao_pericia_medica : null,
            status: (int) ($model->status ?? 0),
            beneficiarioNome: $model->beneficiario->nome ?? null,
            empresaLabel: $model->empresa->razao_social ?? $model->empresa->nome ?? null,
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
