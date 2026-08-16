<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Agendamento\Agendamento as AgendamentoEntity;
use App\Domain\Agendamento\AgendamentoRepositoryInterface;
use App\Domain\Agendamento\AgendamentoSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Agendamento as AgendamentoModel;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentAgendamentoRepository implements AgendamentoRepositoryInterface
{
    public function search(AgendamentoSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = AgendamentoModel::query()->with(['atendimento.beneficiario', 'usuario', 'usuarioAgendamento'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyOpen) {
            $query->where('agendamento.status', '<', 2);
        }
        if ($criteria->restrictToUsuarioId !== null) {
            $query->where('usuario_agendamento_id', $criteria->restrictToUsuarioId);
        }

        if ($criteria->cod !== '' && is_numeric($criteria->cod)) {
            $query->where('agendamento.id', (int) $criteria->cod);
        }

        if ($criteria->usuarioAgendamentoId !== '') {
            $query->where('usuario_agendamento_id', (int) $criteria->usuarioAgendamentoId);
        }

        if ($criteria->status !== '') {
            $query->where('agendamento.status', (int) $criteria->status);
        }

        $paginator = $query->orderByDesc('agendamento.id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        return new PagedResult(
            items: array_map(fn (AgendamentoModel $m) => $this->toEntity($m), $paginator->items()),
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?AgendamentoEntity
    {
        $model = AgendamentoModel::query()->with(['atendimento.beneficiario', 'usuario', 'usuarioAgendamento'])
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function create(array $data): AgendamentoEntity
    {
        $model = AgendamentoModel::query()->create($data);
        $model->load(['atendimento.beneficiario', 'usuario', 'usuarioAgendamento']);

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): AgendamentoEntity
    {
        $model = AgendamentoModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))->find($id);
        if (! $model) {
            throw new RuntimeException('Agendamento Inexistente');
        }
        $model->fill($data);
        $model->save();
        $model->load(['atendimento.beneficiario', 'usuario', 'usuarioAgendamento']);

        return $this->toEntity($model);
    }

    public function formOptions(TenantScope $tenant): array
    {
        $atendimentos = \App\Models\Atendimento::query()
            ->with('beneficiario')
            ->tap(fn (Builder $q) => $this->applyAtendimentoTenant($q, $tenant))
            ->where('status', 1)
            ->orderByDesc('id')
            ->limit(200)
            ->get()
            ->mapWithKeys(function ($a) {
                $nome = $a->beneficiario->nome ?? 'Beneficiário';

                return [$a->id => '#'.$a->id.' — '.$nome];
            })
            ->all();

        return [
            'atendimentos' => $atendimentos,
            'usuarios' => $this->usuarioOptions(),
        ];
    }

    public function atendimentoAllowed(int $atendimentoId, TenantScope $tenant): bool
    {
        return \App\Models\Atendimento::query()
            ->tap(fn (Builder $q) => $this->applyAtendimentoTenant($q, $tenant))
            ->where('id', $atendimentoId)
            ->exists();
    }

    public function usuarioOptions(): array
    {
        return \App\Models\User::query()
            ->where('status', 1)
            ->orderBy('nome')
            ->pluck('nome', 'id')
            ->all();
    }

    private function applyAtendimentoTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->whereHas('beneficiario', fn (Builder $q) => $q->where('cliente_id', $tenant->clienteId));
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('beneficiario.cliente', fn (Builder $q) => $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId));
        }
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->clienteId) {
            $query->whereHas('atendimento.beneficiario', function (Builder $q) use ($tenant) {
                $q->where('cliente_id', $tenant->clienteId);
            });
        } elseif ($tenant->grupoEmpresarialId) {
            $query->whereHas('atendimento.beneficiario.cliente', function (Builder $q) use ($tenant) {
                $q->where('grupo_empresarial_id', $tenant->grupoEmpresarialId);
            });
        }
    }

    private function toEntity(AgendamentoModel $model): AgendamentoEntity
    {
        return new AgendamentoEntity(
            id: $model->id !== null ? (int) $model->id : null,
            atendimentoId: $model->atendimento_id !== null ? (int) $model->atendimento_id : null,
            usuarioId: $model->usuario_id !== null ? (int) $model->usuario_id : null,
            usuarioAgendamentoId: $model->usuario_agendamento_id !== null ? (int) $model->usuario_agendamento_id : null,
            dataHora: $this->toDate($model->data_hora ?? null),
            descricao: $model->descricao !== null ? (string) $model->descricao : null,
            status: (int) ($model->status ?? 0),
            beneficiarioNome: $model->beneficiario->nome ?? null,
            usuarioNome: $model->usuario->nome ?? null,
            usuarioAgendamentoNome: $model->usuarioAgendamento->nome ?? null,
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
