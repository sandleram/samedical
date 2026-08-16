<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Afastado\Afastado as AfastadoEntity;
use App\Domain\Afastado\AfastadoRepositoryInterface;
use App\Domain\Afastado\AfastadoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Afastado as AfastadoModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentAfastadoRepository implements AfastadoRepositoryInterface
{
    public function search(AfastadoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = AfastadoModel::query()->with(['beneficiario.cliente', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('afastado.status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('afastado.id', (int) $criteria->id);
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

        if ($criteria->cpf !== '') {
            $cpf = str_replace(['.', '-'], '', $criteria->cpf);
            $query->whereHas('beneficiario', fn (Builder $bq) => $bq->where('cpf', $cpf));
        }

        if ($criteria->status !== '') {
            $query->where('afastado.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('afastado.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (AfastadoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?AfastadoEntity
    {
        $model = AfastadoModel::query()->with(['beneficiario.cliente', 'empresa'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): AfastadoEntity
    {
        $model = AfastadoModel::query()->create($data);
        $model->load(['beneficiario.cliente', 'empresa']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): AfastadoEntity
    {
        $model = AfastadoModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Afastamento Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['beneficiario.cliente', 'empresa']);

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

    private function toEntity(AfastadoModel $model): AfastadoEntity
    {
        return new AfastadoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            beneficiarioId: $model->beneficiario_id !== null ? (int) $model->beneficiario_id : null,
            empresaId: $model->empresa_id !== null ? (int) $model->empresa_id : null,
            situacao: $model->situacao !== null ? (string) $model->situacao : null,
            dataInicioAfastamento: $this->toDate($model->data_inicio_afastamento ?? null),
            dataFimAfastamento: $this->toDate($model->data_fim_afastamento ?? null),
            cid: $model->cid !== null ? (string) $model->cid : null,
            tipoAfastamento: $model->tipo_afastamento !== null ? (string) $model->tipo_afastamento : null,
            assistenciaMedica: $model->assistencia_medica !== null ? (string) $model->assistencia_medica : null,
            planoAssistenciaMedica: $model->plano_assistencia_medica !== null ? (string) $model->plano_assistencia_medica : null,
            acaoTrabalhista: $model->acao_trabalhista !== null ? (int) $model->acao_trabalhista : null,
            acaoInss: $model->acao_inss !== null ? (int) $model->acao_inss : null,
            limboPrevidenciario: $model->limbo_previdenciario !== null ? (int) $model->limbo_previdenciario : null,
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
