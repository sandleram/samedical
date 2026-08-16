<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Absenteismo\Absenteismo as AbsenteismoEntity;
use App\Domain\Absenteismo\AbsenteismoRepositoryInterface;
use App\Domain\Absenteismo\AbsenteismoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Absenteismo as AbsenteismoModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentAbsenteismoRepository implements AbsenteismoRepositoryInterface
{
    public function search(AbsenteismoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = AbsenteismoModel::query()->with(['beneficiario', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('absenteismo.status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('absenteismo.id', (int) $criteria->id);
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

        if ($criteria->cid !== '') {
            $query->where('cid', 'like', '%'.$criteria->cid.'%');
        }

        if ($criteria->status !== '') {
            $query->where('absenteismo.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('absenteismo.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (AbsenteismoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?AbsenteismoEntity
    {
        $model = AbsenteismoModel::query()->with(['beneficiario', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): AbsenteismoEntity
    {
        $model = AbsenteismoModel::query()->create($data);
        $model->load(['beneficiario', 'empresa']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): AbsenteismoEntity
    {
        $model = AbsenteismoModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Absenteísmo Inexistente');
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

        return [
            'beneficiarios' => $beneficiarios,
            'empresas' => $empresas,
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

    private function toEntity(AbsenteismoModel $model): AbsenteismoEntity
    {
        return new AbsenteismoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            beneficiarioId: $model->beneficiario_id !== null ? (int) $model->beneficiario_id : null,
            empresaId: $model->empresa_id !== null ? (int) $model->empresa_id : null,
            dataSaida: $this->toDate($model->data_saida ?? null),
            dataRetorno: $this->toDate($model->data_retorno ?? null),
            cid: $model->cid !== null ? (string) $model->cid : null,
            hospitalClinica: $model->hospital_clinica !== null ? (string) $model->hospital_clinica : null,
            profissional: $model->profissional !== null ? (string) $model->profissional : null,
            numCrm: $model->num_crm !== null ? (string) $model->num_crm : null,
            qtdeDiasAtestado: $model->qtde_dias_atestado !== null ? (int) $model->qtde_dias_atestado : null,
            observacao: $model->observacao !== null ? (string) $model->observacao : null,
            situacao: $model->situacao !== null ? (string) $model->situacao : null,
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
