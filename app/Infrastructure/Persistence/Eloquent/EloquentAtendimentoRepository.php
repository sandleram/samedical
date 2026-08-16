<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Atendimento\Atendimento as AtendimentoEntity;
use App\Domain\Atendimento\AtendimentoRepositoryInterface;
use App\Domain\Atendimento\AtendimentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Atendimento as AtendimentoModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentAtendimentoRepository implements AtendimentoRepositoryInterface
{
    public function search(AtendimentoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = AtendimentoModel::query()->with(['beneficiario', 'usuario'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('atendimento.status', 1);
        }

        if ($criteria->id !== '' && is_numeric($criteria->id)) {
            $query->where('atendimento.id', (int) $criteria->id);
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

        if ($criteria->status !== '') {
            $query->where('atendimento.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('atendimento.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (AtendimentoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?AtendimentoEntity
    {
        $model = AtendimentoModel::query()->with(['beneficiario', 'usuario'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): AtendimentoEntity
    {
        $model = AtendimentoModel::query()->create($data);
        $model->load(['beneficiario', 'usuario']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): AtendimentoEntity
    {
        $model = AtendimentoModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Atendimento Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['beneficiario', 'usuario']);

        return $this->toEntity($model);
    }

    public function formOptions(TenantScope $tenant): array
    {
        $beneficiarios = \App\Models\Beneficiario::query()
            ->tap(function (Builder $q) use ($tenant) {
                if ($tenant->clienteId) {
                    $q->where('cliente_id', $tenant->clienteId);
                } elseif ($tenant->grupoEmpresarialId) {
                    $q->whereHas('cliente', fn (Builder $cq) => $cq->where('grupo_empresarial_id', $tenant->grupoEmpresarialId));
                }
            })
            ->where('status', 1)
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();

        return ['beneficiarios' => $beneficiarios];
    }

    public function beneficiarioAllowed(int $beneficiarioId, TenantScope $tenant, int $clienteId): bool
    {
        return \App\Models\Beneficiario::query()
            ->where('id', $beneficiarioId)
            ->where('cliente_id', $clienteId)
            ->exists();
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

    private function toEntity(AtendimentoModel $model): AtendimentoEntity
    {
        return new AtendimentoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            beneficiarioId: $model->beneficiario_id !== null ? (int) $model->beneficiario_id : null,
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            tipoAtendimento: $model->tipo_atendimento !== null ? (int) $model->tipo_atendimento : null,
            cid: $model->cid !== null ? (string) $model->cid : null,
            descricao: $model->descricao !== null ? (string) $model->descricao : null,
            formaAtendimento: $model->forma_atendimento !== null ? (int) $model->forma_atendimento : null,
            statusAtendimento: $model->status_atendimento !== null ? (int) $model->status_atendimento : null,
            dataConclusao: $this->toDate($model->data_conclusao ?? null),
            dataCadastro: $this->toDate($model->data_cadastro ?? null),
            status: (int) ($model->status ?? 0),
            beneficiarioNome: $model->beneficiario->nome ?? null,
            usuarioNome: $model->usuario->nome ?? null,
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
