<?php

namespace App\Infrastructure\Persistence\Eloquent;

use App\Domain\Cliente\Cliente as ClienteEntity;
use App\Domain\Cliente\ClienteRepositoryInterface;
use App\Domain\Cliente\ClienteSearchCriteria;
use App\Domain\Shared\PagedResult;
use App\Domain\Shared\TenantScope;
use App\Models\Cliente as ClienteModel;
use App\Models\UsuarioCliente;
use DateTimeImmutable;
use Illuminate\Database\Eloquent\Builder;
use RuntimeException;

final class EloquentClienteRepository implements ClienteRepositoryInterface
{
    public function search(ClienteSearchCriteria $criteria, TenantScope $tenant): PagedResult
    {
        $query = ClienteModel::query()
            ->with('grupoEmpresarial')
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant));

        if ($criteria->onlyActiveForNonRoot) {
            $query->where('status', '<', 2);
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

        $paginator = $query->orderByDesc('id')->paginate(
            perPage: $criteria->perPage,
            page: $criteria->page,
        );

        $items = array_map(
            fn (ClienteModel $model) => $this->toEntity($model),
            $paginator->items(),
        );

        return new PagedResult(
            items: $items,
            total: $paginator->total(),
            perPage: $paginator->perPage(),
            currentPage: $paginator->currentPage(),
        );
    }

    public function findById(int $id, TenantScope $tenant): ?ClienteEntity
    {
        $model = ClienteModel::query()
            ->with('grupoEmpresarial')
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function findForSelecao(int $id): ?ClienteEntity
    {
        $model = ClienteModel::query()
            ->with('grupoEmpresarial')
            ->find($id);

        return $model ? $this->toEntity($model) : null;
    }

    public function listForSelecao(int $usuarioId, int $perfilId, bool $isRoot): array
    {
        $perfilAdm = [1, 2];

        if (in_array($perfilId, $perfilAdm, true) || $isRoot) {
            $query = ClienteModel::query()->with('grupoEmpresarial');

            if (! $isRoot && $perfilId !== 1) {
                $query->where('status', '<', 2);
            }

            $clientes = $query->get();
        } else {
            $clienteIds = UsuarioCliente::query()
                ->where('usuario_id', $usuarioId)
                ->pluck('cliente_id');

            $clientes = ClienteModel::query()
                ->with('grupoEmpresarial')
                ->whereIn('id', $clienteIds)
                ->where('status', '<', 2)
                ->get();
        }

        $sorted = $clientes
            ->sortBy(fn (ClienteModel $c) => ($c->grupoEmpresarial->nome ?? '').'|'.$c->nome)
            ->values();

        return array_map(
            fn (ClienteModel $model) => $this->toEntity($model),
            $sorted->all(),
        );
    }

    public function create(array $data): ClienteEntity
    {
        $model = ClienteModel::query()->create($data);
        $model->load('grupoEmpresarial');

        return $this->toEntity($model);
    }

    public function update(int $id, array $data, TenantScope $tenant): ClienteEntity
    {
        $model = ClienteModel::query()
            ->tap(fn (Builder $q) => $this->applyTenant($q, $tenant))
            ->find($id);

        if (! $model) {
            throw new RuntimeException('Cliente Inexistente');
        }

        $model->fill($data);
        $model->save();
        $model->load('grupoEmpresarial');

        return $this->toEntity($model);
    }

    private function applyTenant(Builder $query, TenantScope $tenant): void
    {
        if ($tenant->grupoEmpresarialId) {
            $query->where($query->getModel()->getTable().'.grupo_empresarial_id', $tenant->grupoEmpresarialId);
        }
    }

    private function toEntity(ClienteModel $model): ClienteEntity
    {
        return new ClienteEntity(
            id: $model->id ? (int) $model->id : null,
            grupoEmpresarialId: $model->grupo_empresarial_id !== null ? (int) $model->grupo_empresarial_id : null,
            nome: (string) ($model->nome ?? ''),
            imgLogo: $model->img_logo,
            status: (int) ($model->status ?? 0),
            dataCadastro: $this->toDateTime($model->data_cadastro),
            dataAtualizacao: $this->toDateTime($model->data_atualizacao),
            dataCancelamento: $this->toDateTime($model->data_cancelamento),
            grupoEmpresarialNome: $model->grupoEmpresarial?->nome,
        );
    }

    private function toDateTime(mixed $value): ?DateTimeImmutable
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
